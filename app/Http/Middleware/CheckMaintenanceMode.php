<?php

namespace App\Http\Middleware;

use App\Services\MaintenanceModeService;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CheckMaintenanceMode
{
    public function __construct(
        protected MaintenanceModeService $maintenanceModeService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->maintenanceModeService->isEnabled()) {
            return $next($request);
        }

        if ($this->isExempt($request)) {
            return $next($request);
        }

        return response()
            ->view('maintenance', [], 503)
            ->header('Retry-After', '3600');
    }

    protected function isExempt(Request $request): bool
    {
        $path = trim($request->path(), '/');

        $routeName = (string) $request->route()?->getName();

        foreach (['livewire.', 'default-livewire.', 'filament.'] as $internalRoutePrefix) {
            if (str_starts_with($routeName, $internalRoutePrefix)) {
                return true;
            }
        }

        if (str_starts_with($path, 'livewire/') || str_starts_with($path, 'livewire-')) {
            return true;
        }

        $technicalPaths = [
            'file-upload',
            'storage',
            'build',
            'css',
            'js',
            'vendor',
            'favicon.ico',
            'up',
        ];

        foreach ($technicalPaths as $technicalPath) {
            if ($path === $technicalPath || str_starts_with($path, $technicalPath.'/')) {
                return true;
            }
        }

        try {
            $panels = collect(Filament::getPanels());

            $hasRootPanel = $panels->contains(
                fn ($panel) => trim((string) $panel->getPath(), '/') === ''
            );

            $panelPaths = $panels
                ->map(fn ($panel) => trim((string) $panel->getPath(), '/'))
                ->filter(fn ($panelPath) => $panelPath !== '')
                ->unique()
                ->values();
        } catch (Throwable $e) {
            $hasRootPanel = false;
            $panelPaths = collect();
        }

        if ($panelPaths->isEmpty()) {
            $panelPaths = collect([
                trim((string) config('filament.path', 'admin'), '/'),
            ]);
        }

        foreach ($panelPaths as $panelPath) {
            if ($path === $panelPath || str_starts_with($path, $panelPath.'/')) {
                return true;
            }
        }

        if ($hasRootPanel) {
            if (auth()->check()) {
                return true;
            }

            $authPaths = [
                'login',
                'logout',
                'password',
            ];

            foreach ($authPaths as $authPath) {
                if ($path === $authPath || str_starts_with($path, $authPath.'/')) {
                    return true;
                }
            }
        }

        return false;
    }
}
