<?php

namespace App\Filament\Admin\Resources\Clients\Tables;

use App\Enums\ClientStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company_name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ClientStatus $state): string => ucfirst($state->value))
                    ->color(fn (ClientStatus $state): string => match ($state) {
                        ClientStatus::Lead => 'warning',
                        ClientStatus::Active => 'success',
                        ClientStatus::Inactive => 'gray',
                    }),
                TextColumn::make('customer.name')
                    ->label('Owner')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(ClientStatus::cases())
                        ->mapWithKeys(fn (ClientStatus $status): array => [
                            $status->value => ucfirst($status->value),
                        ])
                        ->all()),
                SelectFilter::make('customer')
                    ->label('Owner')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
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
