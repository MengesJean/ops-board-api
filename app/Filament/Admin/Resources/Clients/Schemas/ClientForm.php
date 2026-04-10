<?php

namespace App\Filament\Admin\Resources\Clients\Schemas;

use App\Enums\ClientStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Owner (customer)')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('company_name')
                    ->label('Company')
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(50),
                Select::make('status')
                    ->options(collect(ClientStatus::cases())
                        ->mapWithKeys(fn (ClientStatus $status): array => [
                            $status->value => ucfirst($status->value),
                        ])
                        ->all())
                    ->required()
                    ->default(ClientStatus::Lead->value)
                    ->native(false),
                Textarea::make('notes')
                    ->rows(4)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }
}
