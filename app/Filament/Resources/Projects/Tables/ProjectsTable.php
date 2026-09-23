<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Категория')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                ColorColumn::make('accent_theme_color')
                    ->label('Цвет')
                    ->toggleable(),
                TextColumn::make('budget')
                    ->label('Бюджет')
                    ->money('RUB')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('duration')
                    ->label('Срок')
                    ->suffix(' дн.')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_featured')
                    ->label('Избран')
                    ->boolean(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'published' => 'Опубликован',
                        'draft' => 'Черновик',
                        'archived' => 'Архив',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликован',
                        'archived' => 'Архив',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Избранные'),
                SelectFilter::make('category')
                    ->label('Категория')
                    ->options([
                        'landing' => 'Landing Page',
                        'corporate' => 'Корпоративный сайт',
                        'ecommerce' => 'Интернет-магазин',
                        'redesign' => 'Редизайн',
                        'webapp' => 'Веб-приложение',
                        'seo' => 'SEO-продвижение',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}