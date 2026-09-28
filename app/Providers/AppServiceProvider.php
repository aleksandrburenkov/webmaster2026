<?php

namespace App\Providers;

use App\Models\ContactSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['pages.home', 'layouts.app'], function ($view) {
            // ВАЖНО: сохраняем только toArray(), иначе возникнет __PHP_Incomplete_Class
            $contactsSettings = Cache::remember('contacts_settings_contacts', 3600, function () {
                return ContactSetting::getActiveContacts('contacts')
                    ->map(fn(ContactSetting $c) => self::toSettingsArray($c))
                    ->toArray();
            });

            $footerSettings = Cache::remember('contacts_settings_footer', 3600, function () {
                return ContactSetting::getActiveContacts('footer')
                    ->map(fn(ContactSetting $c) => self::toSettingsArray($c))
                    ->toArray();
            });

            $view->with('contactsSettings', $contactsSettings);
            $view->with('footerSettings', $footerSettings);
        });
    }

    private static function toSettingsArray(ContactSetting $contact): array
    {
        return [
            'url' => $contact->url,
            'label' => $contact->label,
            'type' => $contact->type,
            'block' => $contact->block,
            'has_icon' => !empty($contact->icon_file),
            'custom_attrs' => (string) $contact->getCustomAttributesString(),
            'icon_html' => !empty($contact->icon_file)
                ? self::renderSvgInline($contact->icon_file)
                : '',
        ];
    }

    private static function renderSvgInline(string $iconFile): string
    {
        $path = storage_path('app/public/' . $iconFile);
        if (!file_exists($path)) {
            return '';
        }

        $ext = pathinfo($iconFile, PATHINFO_EXTENSION);
        if ($ext !== 'svg') {
            return '<img src="' . e(asset('storage/' . $iconFile)) . '" alt="" class="contact-icon">';
        }

        $svg = file_get_contents($path);
        $svg = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $svg);
        $svg = preg_replace('/\bon\w+\s*=\s*"[^"]*"/i', '', $svg);
        $svg = preg_replace('/\swidth\s*=\s*["\'][^"\']*["\']/i', '', $svg);
        $svg = preg_replace('/\sheight\s*=\s*["\'][^"\']*["\']/i', '', $svg);

        return $svg;
    }
}
