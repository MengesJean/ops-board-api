<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Client;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ownership')
                    ->schema([
                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Client $client): string => sprintf(
                                '%s — %s',
                                $client->name,
                                $client->customer?->name ?? '—'
                            ))
                            ->searchable(['name', 'company_name'])
                            ->preload()
                            ->required(),
                    ]),
                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('reference')
                            ->maxLength(100),
                        Select::make('status')
                            ->options(collect(ProjectStatus::cases())
                                ->mapWithKeys(fn (ProjectStatus $status): array => [
                                    $status->value => self::label($status->value),
                                ])
                                ->all())
                            ->required()
                            ->default(ProjectStatus::Draft->value)
                            ->native(false),
                        Select::make('priority')
                            ->options(collect(ProjectPriority::cases())
                                ->mapWithKeys(fn (ProjectPriority $priority): array => [
                                    $priority->value => ucfirst($priority->value),
                                ])
                                ->all())
                            ->required()
                            ->default(ProjectPriority::Medium->value)
                            ->native(false),
                        Select::make('health')
                            ->options(collect(ProjectHealth::cases())
                                ->mapWithKeys(fn (ProjectHealth $health): array => [
                                    $health->value => ucfirst($health->value),
                                ])
                                ->all())
                            ->required()
                            ->default(ProjectHealth::Good->value)
                            ->native(false),
                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ]),
                Section::make('Timeline & Budget')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('start_date')
                            ->native(false),
                        DatePicker::make('due_date')
                            ->native(false)
                            ->afterOrEqual('start_date'),
                        TextInput::make('budget')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step('0.01'),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(4)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function label(string $value): string
    {
        return ucfirst(str_replace('_', ' ', $value));
    }
}
