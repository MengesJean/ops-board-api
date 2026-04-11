<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tasks';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(collect(TaskStatus::cases())
                        ->mapWithKeys(fn (TaskStatus $status): array => [
                            $status->value => self::label($status->value),
                        ])
                        ->all())
                    ->required()
                    ->default(TaskStatus::Todo->value)
                    ->native(false),
                Select::make('priority')
                    ->options(collect(TaskPriority::cases())
                        ->mapWithKeys(fn (TaskPriority $priority): array => [
                            $priority->value => self::label($priority->value),
                        ])
                        ->all())
                    ->required()
                    ->default(TaskPriority::Medium->value)
                    ->native(false),
                Select::make('project_milestone_id')
                    ->label('Milestone')
                    ->options(fn () => $this->ownerRecord->milestones()->pluck('title', 'id')->all())
                    ->nullable()
                    ->native(false)
                    ->placeholder('— No milestone —'),
                DatePicker::make('due_date')
                    ->native(false),
                Textarea::make('description')
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('position')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('milestone.title')
                    ->label('Milestone')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TaskStatus $state): string => self::label($state->value))
                    ->color(fn (TaskStatus $state): string => match ($state) {
                        TaskStatus::Todo => 'gray',
                        TaskStatus::InProgress => 'info',
                        TaskStatus::Done => 'success',
                    }),
                TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (TaskPriority $state): string => self::label($state->value))
                    ->color(fn (TaskPriority $state): string => match ($state) {
                        TaskPriority::Low => 'gray',
                        TaskPriority::Medium => 'warning',
                        TaskPriority::High => 'danger',
                    }),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('position')
            ->reorderable('position')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(TaskStatus::cases())
                        ->mapWithKeys(fn (TaskStatus $status): array => [
                            $status->value => self::label($status->value),
                        ])
                        ->all()),
                SelectFilter::make('priority')
                    ->options(collect(TaskPriority::cases())
                        ->mapWithKeys(fn (TaskPriority $priority): array => [
                            $priority->value => self::label($priority->value),
                        ])
                        ->all()),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function label(string $value): string
    {
        return ucfirst(str_replace('_', ' ', $value));
    }
}
