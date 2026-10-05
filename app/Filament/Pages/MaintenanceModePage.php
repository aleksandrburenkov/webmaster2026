<?php

namespace App\Filament\Pages;

use App\Services\MaintenanceModeService;
use BackedEnum;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class MaintenanceModePage extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Администрирование';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Режим обслуживания';

    protected static ?string $title = 'Режим обслуживания';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.maintenance-mode';

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'maintenance_mode' => app(MaintenanceModeService::class)->isEnabled(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Режим обслуживания')
                    ->description('Управление доступностью публичной части сайта.')
                    ->schema([
                        Toggle::make('maintenance_mode')
                            ->label('Режим обслуживания')
                            ->helperText('Если включено, посетители сайта увидят страницу обслуживания. Админ-панель останется доступной.')
                            ->live()
                            ->afterStateUpdated(function (bool $state): void {
                                app(MaintenanceModeService::class)->enable($state);

                                Notification::make()
                                    ->title($state ? 'Режим обслуживания включён' : 'Режим обслуживания выключен')
                                    ->success()
                                    ->send();
                            }),
                    ]),
            ])
            ->statePath('data');
    }
}
