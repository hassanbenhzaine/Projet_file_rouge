<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class OrderController extends Controller
{
    public function add($addressId){

        if(isset(Order::latest()->first()->number)){
            $lastOrderId = ++Order::latest()->first()->number;
        } else{
            $lastOrderId = 1;
        }

        $orderInfo = [];
        foreach (Cart::content() as $item) {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->product_id = $item->id;
            $order->quantity = $item->qty;
            $order->number = $lastOrderId;
            $order->status = 'pending';
            $order->address_id = $addressId;
            $order->save();
            $orderInfo['number'] = $order->number;
            $orderInfo['created_at'] = $order->created_at;
            $orderInfo['total'] = Cart::total();

            $product['id'] = $order->product_id;
            $product['qty'] = $order->quantity;
            $orderInfo['products'][] = $product;
        }

        return $orderInfo;
    }

    public function ordersWithUserAndProducts($limit, $searchQuery){

        if(isset($searchQuery)){
            $orders = DB::table('orders')
            ->select('orders.number', 'users.email', 'products.id as productId', 'products.title', 'orders.quantity', 'orders.created_at', 'orders.updated_at', 'orders.status')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.number', 'like', '%'.$searchQuery.'%')
            ->orWhere('users.email', 'like', '%'.$searchQuery.'%')
            ->orderBy('orders.number', 'desc')
            ->paginate($limit);
        } else{
            $orders = DB::table('orders')
            ->select('orders.number', 'users.email', 'products.id as productId', 'products.title', 'orders.quantity', 'orders.created_at', 'orders.updated_at', 'orders.status')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->orderBy('orders.number', 'desc')
            ->paginate($limit);
        }

        return $orders;

    } 

    public function customerOrdersWithProducts($limit, $searchQuery){

        if(isset($searchQuery)){
            $orders = DB::table('orders')
            ->select('orders.number', 'products.id as productId', 'products.title', 'orders.quantity', 'orders.created_at', 'orders.status')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.user_id' ,'=', Auth::id())
            ->where('orders.number', 'like', '%'.$searchQuery.'%')
            ->orWhere('products.title', 'like', '%'.$searchQuery.'%')
            ->orderBy('orders.number', 'desc')
            ->paginate($limit);
        } else{
            $orders = DB::table('orders')
            ->select('orders.number', 'products.id as productId', 'products.title', 'orders.quantity', 'orders.created_at', 'orders.status')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.user_id' ,'=', Auth::id())
            ->orderBy('orders.number', 'desc')
            ->paginate($limit);
        }

        return $orders;

    }

    public function destroy($orders){
        DB::table('orders')
        ->whereIn('number', $orders)
        ->delete();

    }

    public function show($number){
       $order =  DB::table('orders')
        ->where('number', '=', $number)
        ->get();

        return $order;
    }

    public function edit($orderNumber, $editStatus)
    {
        Order::where('number', $orderNumber)
            ->update(["status" => $editStatus]);
    }

    public function cancel($orderNumber)
    {
        Order::where('number', $orderNumber)
            ->where('user_id', '=', Auth::id())
            ->update(["status" => 'pending_delete']);
    }

    public function undoCancel($orderNumber)
    {
        Order::where('number', $orderNumber)
            ->where('user_id', '=', Auth::id())
            ->update(["status" => 'pending']);
    }
    

}
