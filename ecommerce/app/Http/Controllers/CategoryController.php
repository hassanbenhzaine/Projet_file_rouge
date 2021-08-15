<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($limit)
    {
        if($limit == null){
            $products = DB::table('categories')
            ->get();
        } else{
            $products = DB::table('categories')
            ->limit($limit)
            ->get();
        }


        return  $products;
    }

    public function popular(){
        $categories = DB::table('categories')
        ->get();

        return  $categories;
    }
}
