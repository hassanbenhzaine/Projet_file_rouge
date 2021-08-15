<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Livewire\Component;

class Search extends Component
{
    public $selectedCategory = [];
    public $minPrice = 0;
    public $maxPrice = 99999;
    public $query;

    public $qty = 1;

    protected $listeners = [
        '$refresh'
    ];

    public function render()
    {
        if(!isset($this->query)){
            $this->query = request()->q;
        }

                
        $productController = new ProductController;
        $products = $productController->search($this->query, 12, $this->selectedCategory,$this->minPrice,$this->maxPrice );

        $categoryController = new CategoryController;
        $categories = $categoryController->index(10);

        return view('livewire.search', ['products' => $products, 'categories' => $categories]);
    }

    public function addToCart($id){
        $cartController = new CartController;
        $cartController->add($id, $this->qty);

        $this->emitTo('navigation', '$refresh');
    }

    public function removeFromCart($id){
        $cartController = new CartController;
        $cartController->remove($id);

        $this->emitTo('navigation', '$refresh');
    }

    public function updateFilter($query){
        $this->query = $query;
    }
}
