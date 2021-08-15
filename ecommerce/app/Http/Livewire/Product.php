<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ProductController;
use Livewire\Component;

class Product extends Component
{
    public function render()
    {
        $productController = new ProductController;
        $product = $productController->productWithCategory(request()->id);

        return view('livewire.product', ['product' => $product]);
    }
}
