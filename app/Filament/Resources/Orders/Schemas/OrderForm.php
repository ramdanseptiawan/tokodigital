<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_id')
                    ->label('Order ID')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                    
                TextInput::make('customer_name')
                    ->label('Nama Customer')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('customer_email')
                    ->label('Email Customer')
                    ->required()
                    ->email()
                    ->maxLength(255),
                    
                TextInput::make('customer_phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(20),
                    
                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),
                    
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false),
                    
                TextInput::make('midtrans_transaction_id')
                    ->label('Midtrans Transaction ID')
                    ->maxLength(255)
                    ->disabled(),
                    
                Textarea::make('qr_string')
                    ->label('QR String')
                    ->rows(3)
                    ->disabled(),
            ]);
    }
}
