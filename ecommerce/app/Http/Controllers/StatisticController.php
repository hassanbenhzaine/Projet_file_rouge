<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    
    public function OrdersPerMonth(){
        $orders = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '%M') as month, COUNT(id) as count")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        
        $statistics = []; 
        foreach ($orders as $order) {
            $statistics['count'][] = $order->count; 
            $statistics['month'][] = $order->month; 
        }

        return $statistics;
    }

}
