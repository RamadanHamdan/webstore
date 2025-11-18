<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Illuminate\Support\Number;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\Computed;

class ProductData extends Data
{
    #[Computed]
    public string $price_formatted;
    public function __construct(
        public string $name,
        public string $sku,
        public string $slug,
        public string|Optional|null $description,
        public int $stock,
        public float $price,
        public int $weight,
        public string $cover_url,
    ) {
        $this->price_formatted = Number::currency($price);
    }

    public static function fromModel(Product $product): self
    {
        $relativeUrl = $product->getFirstMediaUrl('cover');
        return new self(
            name: $product->name,
            sku: $product->sku,
            slug: $product->slug,
            description: $product->description,
            stock: $product->stock,
            price: Floatval($product->price),
            weight: $product->weight,
            cover_url: url($relativeUrl),
        );
    }
}
