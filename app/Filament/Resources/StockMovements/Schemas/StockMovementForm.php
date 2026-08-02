<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('movement_type')
                    ->options(['in' => 'In', 'out' => 'Out'])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
