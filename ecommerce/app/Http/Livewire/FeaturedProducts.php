<?php

namespace App\Http\Livewire;

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Livewire\Component;

class FeaturedProducts extends Component
{
    public $amount = 8;
    public $qty = 1;
    public $showButton = true;

    protected $listeners = [
        '$refresh'
    ];

    public function render()
    {
        $productController = new ProductController;
        $featuredProductsR = $productController->featuredProducts($this->amount);

        if($featuredProductsR['count'] < $this->amount){
            $this->showButton = false;
        }

        return view('livewire.featured-products', ['featuredProducts' => $featuredProductsR['data']]);
    }

    public function load(){
        $this->amount += 4;
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
}
