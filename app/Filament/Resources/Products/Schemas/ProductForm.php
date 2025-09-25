<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                    
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash']),
                    
                Select::make('type')
                    ->label('Jenis Produk')
                    ->required()
                    ->options([
                        'game' => 'Game',
                        'ebook' => 'E-book',
                        'workflow' => 'Workflow',
                        'module' => 'Module',
                    ])
                    ->native(false),
                    
                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0),
                    
                FileUpload::make('cover_image')
                    ->label('Cover Image')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Upload gambar produk (max 2MB). Format: JPEG, PNG, WebP'),
                    
                TextInput::make('cover_url')
                    ->label('URL Cover (Opsional)')
                    ->url()
                    ->maxLength(500)
                    ->helperText('Atau masukkan URL gambar jika tidak upload file'),
                    
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(4),
                    
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->helperText('Informasi tambahan seperti platform, genre, format, dll.'),
                    
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
