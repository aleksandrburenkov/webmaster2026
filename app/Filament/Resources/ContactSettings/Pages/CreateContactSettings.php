<?php

namespace App\Filament\Resources\ContactSettings\Pages;

use App\Filament\Resources\ContactSettings\ContactSettingsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactSettings extends CreateRecord
{
    protected static string $resource = ContactSettingsResource::class;
}