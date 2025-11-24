<?php

namespace App\Livewire;

use App\Contract\CartServiceInterface;
use Livewire\Component;
use App\Data\ProductData;

class CartItemRemove extends Component
{
    public string $sku;

    public function mount(ProductData $product)
    {
        $this->sku = $product->sku;
    }

    public function remove(CartServiceInterface $cart)
    {
        $cart->remove($this->sku);
        session()->flash('success', "Produk {$this->sku} Sudah Dihapus");

        $this->dispatch('cart-updated');

        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.cart-item-remove');
    }
}
