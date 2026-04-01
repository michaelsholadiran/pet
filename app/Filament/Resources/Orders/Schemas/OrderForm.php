<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('discount_code_id')
                    ->relationship('discountCode', 'code')
                    ->searchable()
                    ->preload(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('¢'),
                TextInput::make('discount_amount')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->suffix('¢'),
                Select::make('status')
                    ->options(Order::statuses())
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_status'),
                TextInput::make('payment_method'),
                TextInput::make('tracking_number'),
                TextInput::make('tracking_url')
                    ->url(),
                DateTimePicker::make('shipped_at'),
                Textarea::make('shipping_address')
                    ->columnSpanFull(),
                TextInput::make('email')->email(),
                TextInput::make('fullname')->label('Full name'),
                TextInput::make('phone')->tel(),
            ]);
    }
}
