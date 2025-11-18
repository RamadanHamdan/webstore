<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpan('full')
                    ->label('Product Details')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Product Cover Image')
                            ->collection('cover'),
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->multiple(),
                        TextInput::make('name')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true),
                        SpatieTagsInput::make('tags')
                            ->type('collection')
                            ->label('Collections'),
                        TextInput::make('stock')
                            ->label('Stock Quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('price')
                            ->label('Price')
                            ->prefix('Rp')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('weight')
                            ->label('Weight (grams)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('grams'),
                    ]),
            ]);
    }
}
