<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Tag;
use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;
use App\Data\ProductCollectionData;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    public $queryString = [
        'selected_collections' => ['except' => []],
        'sort_by' => ['except' => 'newest'],
        'search' => ['except' => []],
    ];

    use WithPagination;

    public array $selected_collections = [];

    public string $search = '';

    public string $sort_by = 'newest';

    public function mount()
    {
        $this->validate();
    }

    protected function rules()
    {
        return [
            'selected_collections' => 'array',
            'selected_collections.*' => 'integer|exists:tags,id',
            'search' => 'nullable|string|min:3|max:30',
            'sort_by' => 'in:newest,latest,price_asc,price_desc',
        ];
    }

    public function applyFilters()
    {
        $this->validate();
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->selected_collections = [];
        $this->sort_by = 'newest';
        $this->search = '';

        $this->resetErrorBag();
        $this->resetPage();
    }

    public function render()
    {
        // Early Return
        $collections = ProductCollectionData::collect([]);
        $products = ProductData::collect([]);
        if ($this->getErrorBag()->isNotEmpty()) {
            return view('livewire.product-catalog', compact('collections', 'products'));
        }

        $collection_result = Tag::query()->withType('collection')->withCount('products')->get();
        $query = Product::query();
        if ($this->search) {
            $query->where('name', 'LIKE', "%{$this->search}%");
        }

        if (!empty($this->selected_collections)) {
            $query->whereHas('tags', function ($query) {
                $query->whereIn('id', $this->selected_collections);
            });
        }

        switch ($this->sort_by) {
            case 'latest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
            default:
                $query->latest();
                break;
        }

        // $result = Product::paginate(2);

        $products = ProductData::collect($query->paginate(2));
        $collections = ProductCollectionData::collect($collection_result);

        return view('livewire.product-catalog', compact('products', 'collections'));
    }
}
