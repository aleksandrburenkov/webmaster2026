<?php

namespace App\Filament\Resources\ContactSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основные настройки')
                    ->schema([
                        Select::make('type')
                            ->label('Тип контакта')
                            ->options([
                                'max' => 'Max',
                                'email' => 'Email',
                                'vk' => 'ВКонтакте',
                                'whatsapp' => 'WhatsApp',
                                'site' => 'Сайт',
                                'other' => 'Другое',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Select::make('block')
                            ->label('Блок размещения')
                            ->options([
                                'contacts' => 'Секция Контакты',
                                'footer' => 'Футер',
                            ])
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('label')
                            ->label('Отображаемый текст')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        FileUpload::make('icon_file')
                            ->label('Иконка')
                            ->disk('public')
                            ->directory('icons/contacts')
                            ->acceptedFileTypes([
                                'image/svg+xml',
                                'image/svg',
                                'image/png',
                                'image/jpeg',
                                'image/jpg',
                                'text/plain',
                                'text/xml',
                            ])
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Ссылка и значение')
                    ->schema([
                        TextInput::make('url')
                            ->label('URL / Ссылка')
                            ->url()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        TextInput::make('value')
                            ->label('Значение (email / телефон)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Отображение')
                    ->schema([
                        TextInput::make('display_order')
                            ->label('Порядок отображения')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true)
                            ->columnSpan(1),

                        KeyValue::make('custom_attributes')
                            ->label('Дополнительные атрибуты')
                            ->keyLabel('Атрибут')
                            ->valueLabel('Значение')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}