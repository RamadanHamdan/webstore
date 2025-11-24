<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use App\Data\ProductData;
use Spatie\LaravelData\Data;
use Livewire\Attributes\Computed;

class CartItemData extends Data
{
    public function __construct(
        public string $sku,
        public string $quantity,
        public string $price,
        public string $weight,
    ) {}

    #[Computed()]
    public function product(): ProductData
    {
        return ProductData::fromModel(
            Product::where('sku', $this->sku)->first()
        );
    }
}
