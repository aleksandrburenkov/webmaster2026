<?php

namespace App\Filament\Resources\ContactSettings;

use App\Filament\Resources\ContactSettings\Pages\CreateContactSettings;
use App\Filament\Resources\ContactSettings\Pages\EditContactSettings;
use App\Filament\Resources\ContactSettings\Pages\ListContactSettings;
use App\Filament\Resources\ContactSettings\Schemas\ContactSettingsForm;
use App\Filament\Resources\ContactSettings\Tables\ContactSettingsTable;
use App\Models\ContactSetting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ContactSettingsResource extends Resource
{
    protected static ?string $model = ContactSetting::class;

    protected static ?int $navigationSort = 10;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-phone';
    }

    public static function getNavigationLabel(): string
    {
        return 'Контакты и ссылки';
    }

    public static function getModelLabel(): string
    {
        return 'Контакт';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Контакты и ссылки';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Администрирование';
    }

    public static function form(Schema $schema): Schema
    {
        return ContactSettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactSettings::route('/'),
            'create' => Pages\CreateContactSettings::route('/create'),
            'edit' => Pages\EditContactSettings::route('/{record}/edit'),
        ];
    }
}