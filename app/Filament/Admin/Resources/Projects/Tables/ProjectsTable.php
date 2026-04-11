<?php

namespace App\Filament\Admin\Resources\Projects\Tables;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reference')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.customer.name')
                    ->label('Owner')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state): string => ucfirst(str_replace('_', ' ', $state->value)))
                    ->color(fn (ProjectStatus $state): string => match ($state) {
                        ProjectStatus::Draft => 'gray',
                        ProjectStatus::Planned => 'info',
                        ProjectStatus::Active => 'success',
                        ProjectStatus::OnHold => 'warning',
                        ProjectStatus::Completed => 'gray',
                        ProjectStatus::Cancelled => 'danger',
                    }),
                TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (ProjectPriority $state): string => ucfirst($state->value))
                    ->color(fn (ProjectPriority $state): string => match ($state) {
                        ProjectPriority::Low => 'gray',
                        ProjectPriority::Medium => 'info',
                        ProjectPriority::High => 'danger',
                    }),
                TextColumn::make('health')
                    ->badge()
                    ->formatStateUsing(fn (ProjectHealth $state): string => ucfirst($state->value))
                    ->color(fn (ProjectHealth $state): string => match ($state) {
                        ProjectHealth::Good => 'success',
                        ProjectHealth::Warning => 'warning',
                        ProjectHealth::Critical => 'danger',
                    }),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
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
                    ->options(collect(ProjectStatus::cases())
                        ->mapWithKeys(fn (ProjectStatus $status): array => [
                            $status->value => ucfirst(str_replace('_', ' ', $status->value)),
                        ])
                        ->all()),
                SelectFilter::make('priority')
                    ->options(collect(ProjectPriority::cases())
                        ->mapWithKeys(fn (ProjectPriority $priority): array => [
                            $priority->value => ucfirst($priority->value),
                        ])
                        ->all()),
                SelectFilter::make('health')
                    ->options(collect(ProjectHealth::cases())
                        ->mapWithKeys(fn (ProjectHealth $health): array => [
                            $health->value => ucfirst($health->value),
                        ])
                        ->all()),
                SelectFilter::make('client')
                    ->relationship('client', 'name')
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
