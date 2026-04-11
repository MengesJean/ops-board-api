<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use App\Enums\MilestoneStatus;
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

class MilestonesRelationManager extends RelationManager
{
    protected static string $relationship = 'milestones';

    protected static ?string $title = 'Milestones';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(collect(MilestoneStatus::cases())
                        ->mapWithKeys(fn (MilestoneStatus $status): array => [
                            $status->value => self::label($status->value),
                        ])
                        ->all())
                    ->required()
                    ->default(MilestoneStatus::Pending->value)
                    ->native(false),
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
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (MilestoneStatus $state): string => self::label($state->value))
                    ->color(fn (MilestoneStatus $state): string => match ($state) {
                        MilestoneStatus::Pending => 'gray',
                        MilestoneStatus::InProgress => 'info',
                        MilestoneStatus::Done => 'success',
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
                    ->options(collect(MilestoneStatus::cases())
                        ->mapWithKeys(fn (MilestoneStatus $status): array => [
                            $status->value => self::label($status->value),
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
