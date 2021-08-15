<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Mail\OrderConfirmed;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;





class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($id, $qty)
    {
        $productController = new ProductController;
        $product = $productController->show($id);

        Cart::add($id, $product->title, $qty, $product->price);
    }

    public function remove($id)
    {
        Cart::remove($id);
    }


    public function cartSubmit(Request $request){
        \Stripe\Stripe::setApiKey(env('STRIPE_API_SECRET_KEY'));

        $intent = \Stripe\PaymentIntent::retrieve($request->strip);

        if($intent->status == "succeeded"){

            $addressId = $request->selectedAddress;

            if($request->selectedAddress == null){
                $addressController = new AddressController;
                $addressId = $addressController->addOnCheckout($request, Auth::id());
                
            }

            $orderController = new OrderController;
            $orderInfo = $orderController->add($addressId);

            Cart::destroy();

            //send confirmation email
            $user = Auth::user();
            Mail::to(Auth::user()->email)->send(new OrderConfirmed($user, $orderInfo, $addressId));

            return view('cart-success', ['orderInfo' => $orderInfo]);
        }
    }

   }
