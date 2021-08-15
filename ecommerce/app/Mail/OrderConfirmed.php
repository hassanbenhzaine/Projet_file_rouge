<?php

namespace App\Mail;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $orderInfo;
    public $orderedProducts;
    public $address;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $orderInfo, $addressId)
    {
        $this->user = $user;
        $this->orderInfo = $orderInfo;

        $addressController = new AddressController;
        $address = $addressController->show($addressId);

        $this->address = $address;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        for($i=0; $i < count($this->orderInfo['products']); $i++) { 
            $productsIds[] = $this->orderInfo['products'][$i]['id'];
        }

        $productController = new ProductController;
        $this->orderedProducts = $productController->showMultiple($productsIds);
        

        return $this->markdown('emails.order-confirmed');
    }
}
