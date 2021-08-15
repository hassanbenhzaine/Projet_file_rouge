<?php

namespace App\Http\Livewire;

use App\Http\Controllers\AddressController;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;



class CartMain extends Component
{

    public $showAddressInputs = false;
    public $customerAddresses;


    public function showAddAddress(){
        $this->showAddressInputs = true;
    }

    public function render()
    {
        if(!Cart::content()->count() > 0){
            redirect(route('home'));
        }

        $data = null;

        if(Cart::content()->count() > 0){
            \Stripe\Stripe::setApiKey(env('STRIPE_API_SECRET_KEY'));
            $data['intent'] = \Stripe\PaymentIntent::create([
                'amount' => Cart::priceTotal(2,'',''),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                // Verify your integration in this guide by including this parameter
                'metadata' => ['integration_check' => 'accept_a_payment', 'userId' => Auth::id(), 'email' => Auth::user()->email],
            ]);
        }

        $addressController = new AddressController;
        $data['address'] = $addressController->show(Auth::id());

        $addressController = new AddressController;
        $this->customerAddresses = $addressController->customerAddresses();


        return view('livewire.cart-main', ['data' => $data]);
    }

    public function deleteFromCart($rowId){
        Cart::remove($rowId);
        $this->emitTo('navigation', '$refresh');
    }

    
}
