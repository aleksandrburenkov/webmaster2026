<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('project_tabs')
                    ->tabs([
                        Tabs\Tab::make('Основное')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Название проекта')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                                        if ($operation === 'create' && $state) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label('URL-идентификатор')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Select::make('category')
                                    ->label('Категория')
                                    ->options([
                                        'landing' => 'Landing Page',
                                        'corporate' => 'Корпоративный сайт',
                                        'ecommerce' => 'Интернет-магазин',
                                        'redesign' => 'Редизайн',
                                        'webapp' => 'Веб-приложение',
                                        'seo' => 'SEO-продвижение',
                                    ]),
                                TextInput::make('client')
                                    ->label('Клиент')
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Год')
                                    ->numeric()
                                    ->minValue(2010)
                                    ->maxValue(2030),
                                RichEditor::make('short_description')
                                    ->label('Краткое описание')
                                    ->columnSpanFull(),
                                RichEditor::make('description')
                                    ->label('Полное описание')
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tabs\Tab::make('Медиа')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->label('Превью-изображение')
                                    ->image()
                                    ->directory('projects/thumbnails')
                                    ->disk('public')
                                    ->imageEditor()
                                    ->maxSize(5120),
                                Repeater::make('gallery')
                                    ->label('Галерея изображений')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Изображение')
                                            ->image()
                                            ->directory('projects/gallery')
                                            ->disk('public')
                                            ->maxSize(5120),
                                        TextInput::make('alt')
                                            ->label('Alt-текст')
                                            ->maxLength(255),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Бюджет и сроки')
                            ->schema([
                                TextInput::make('budget')
                                    ->label('Бюджет (₽)')
                                    ->numeric()
                                    ->prefix('₽')
                                    ->minValue(0)
                                    ->maxValue(99999999),
                                TextInput::make('duration')
                                    ->label('Срок реализации (дней)')
                                    ->numeric()
                                    ->suffix('дней')
                                    ->minValue(0)
                                    ->maxValue(365),
                                ColorPicker::make('accent_theme_color')
                                    ->label('Фирменный цвет проекта')
                                    ->rgba(),
                                Repeater::make('services_list')
                                    ->label('Список услуг')
                                    ->schema([
                                        TextInput::make('service')
                                            ->label('Услуга')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tabs\Tab::make('Метрики и SEO')
                            ->schema([
                                KeyValue::make('metrics')
                                    ->label('Ключевые метрики')
                                    ->keyLabel('Показатель')
                                    ->valueLabel('Значение')
                                    ->columnSpanFull(),
                                KeyValue::make('seo_metadata')
                                    ->label('SEO-метаданные')
                                    ->keyLabel('Параметр')
                                    ->valueLabel('Значение')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Публикация')
                            ->schema([
                                Select::make('status')
                                    ->label('Статус')
                                    ->options([
                                        'draft' => 'Черновик',
                                        'published' => 'Опубликован',
                                        'archived' => 'В архиве',
                                    ])
                                    ->default('draft')
                                    ->required(),
                                Toggle::make('is_featured')
                                    ->label('Избранный проект')
                                    ->default(false),
                                TextInput::make('sort_order')
                                    ->label('Порядок сортировки')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(9999),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }
}
