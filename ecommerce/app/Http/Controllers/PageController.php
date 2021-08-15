<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('home');
    }

    public function search()
    {
        return view('search');
    }

    public function cart()
    {
        if(!Cart::content()->count() > 0){
            return view('home');
        }
        return view('cart');
    }

    public function product()
    {
        return view('product');
    }

    public function dashboard($view = 'home')
    {
        return view('dashboard', ['view' => $view]);
    }
    
    public function checkoutSuccess(){
        return view('cart-success');
    }


    public function category($name){
        return view('category', ['category' => $name]);
    }

    
}
