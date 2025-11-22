<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Spatie\LaravelData\Data;
use Illuminate\Support\Number;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Optional as LaravelDataOptional;

class ProductData extends Data
{
    #[Computed]
    public string $price_formatted;
    public function __construct(
        public string $name,
        public string|null $short_desc,
        public string $tags,
        public string $sku,
        public string $slug,
        public string|LaravelDataOptional|null $description,
        public int $stock,
        public float $price,
        public int $weight,
        public string $cover_url,
        public LaravelDataOptional|array $gallery = new LaravelDataOptional()
    ) {
        $this->price_formatted = Number::currency($price);
    }

    public static function fromModel(Product $product, bool $with_gallery = false): self
    {
        $relativeUrl = $product->getFirstMediaUrl('cover');
        return new self(
            name: $product->name,
            short_desc: $product->tags->where('type', 'collection')->pluck('name')->implode(', '),
            tags: $product->tags->where('type', 'collection')->pluck('name')->implode(', '),
            sku: $product->sku,
            slug: $product->slug,
            description: $product->description,
            stock: $product->stock,
            price: Floatval($product->price),
            weight: $product->weight,
            cover_url: url($relativeUrl),
            gallery: $with_gallery ? $product->getMedia('gallery')->map(fn($record) => $record->getUrl())->toArray() : new LaravelDataOptional(),
        );
    }
}
