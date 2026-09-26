<?php

namespace App\Filament\Resources\ContactSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'max' => 'info',
                        'email' => 'success',
                        'vk' => 'primary',
                        'whatsapp' => 'success',
                        'site' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'max' => 'Max',
                        'email' => 'Email',
                        'vk' => 'VK',
                        'whatsapp' => 'WhatsApp',
                        'site' => 'Сайт',
                        'other' => 'Другое',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('block')
                    ->label('Блок')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'contacts' => 'success',
                        'footer' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'contacts' => 'Контакты',
                        'footer' => 'Футер',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label('Ссылка')
                    ->searchable()
                    ->toggleable()
                    ->limit(40),

                TextColumn::make('display_order')
                    ->label('Порядок')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('block')
                    ->label('Блок')
                    ->options([
                        'contacts' => 'Контакты',
                        'footer' => 'Футер',
                    ]),

                SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'max' => 'Max',
                        'email' => 'Email',
                        'vk' => 'VK',
                        'whatsapp' => 'WhatsApp',
                        'site' => 'Сайт',
                        'other' => 'Другое',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('block')
            ->defaultSort('display_order');
    }
}