<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;


use Illuminate\Http\Request;

class ProductController extends Controller
{
    use WithFileUploads;


    public $photos;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function featuredProducts($limit)
    {
       
        $products['data'] = DB::table('products')
                    ->limit($limit)
                    ->get();

        $products['count'] = DB::table('products')
                    ->get()
                    ->count();
                
        return $products;
    }

    public function search($query, $limit, $selectCategory, $minPrice, $maxPrice)
    {
        
        if($query == null){
            switch (true) {
                case !empty($minPrice) && !empty($maxPrice) && !empty($selectCategory):
                    $products = DB::table('products')
                    ->whereIn('category_id', $selectCategory)
                    ->whereBetween('price', [$minPrice, $maxPrice])
                    ->paginate($limit);
                    break;
            
                case !empty($minPrice) && !empty($maxPrice):
                    
                    $products = DB::table('products')
                    ->whereBetween('price', [$minPrice, $maxPrice])
                    ->paginate($limit);
                    break;

                case !empty($selectCategory):
                    
                    $products = DB::table('products')
                    ->whereIn('category_id', $selectCategory)
                    ->paginate($limit);
                    break;
                default:

                    $products = DB::table('products')
                    ->paginate($limit);
                    break;
            }
        } else{
            switch (true) {
                case !empty($minPrice) && !empty($maxPrice) && !empty($selectCategory):
                    $products = DB::table('products')
                    ->whereIn('category_id', $selectCategory)
                    ->whereBetween('price', [$minPrice, $maxPrice])
                    ->where('title', 'like', '%'.$query.'%')
                    ->paginate($limit);
                    break;
            
                case !empty($minPrice) && !empty($maxPrice):
                    $products = DB::table('products')
                    ->whereBetween('price', [$minPrice, $maxPrice])
                    ->where('title', 'like', '%'.$query.'%')
                    ->paginate($limit);
                    break;

                case !empty($selectCategory):
                    $products = DB::table('products')
                    ->whereIn('category_id', $selectCategory)
                    ->where('title', 'like', '%'.$query.'%')
                    ->paginate($limit);
                    break;
                default:

                    $products = DB::table('products')
                    ->where('title', 'like', '%'.$query.'%')
                    ->paginate($limit);
                    break;
            }
        }
    
        return $products;
        
    }
           

    public function productWithCategory($id){
        $product = DB::table('products')
            ->select('products.id as productId','products.title','products.description','products.price','products.photos','products.category_id','products.condition' , 'categories.name')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.id', '=', $id)
            ->first();

        return $product;
    }

    public function show($id){
        $product = DB::table('products')
            ->where('products.id', '=', $id)
            ->first();

        return $product;
    }

    public function showMultiple($ids){
        $products = DB::table('products')
            ->whereIn('products.id', $ids)
            ->get();

        return $products;
    }

    public function destroy($products){
        DB::table('products')
        ->whereIn('id', $products)
        ->delete();

    }

    public function productsWithCategories($limit, $searchQuery){
        
        if(isset($searchQuery)){
            $products = DB::table('products')
            ->select('products.id AS productId', 'products.title', 'products.description', 'products.price', 'products.created_at', 'products.updated_at',
            'categories.id AS category_id', 'categories.name')
            ->where('products.title', 'like', '%'.$searchQuery.'%')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->paginate($limit);
        } else{
            $products = DB::table('products')
            ->select('products.id AS productId', 'products.title', 'products.description', 'products.price', 'products.created_at', 'products.updated_at',
            'categories.id AS category_id', 'categories.name')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->paginate($limit);
        }

        return $products;
    }

    public function productsByCategoryName($name, $limit){

        $products['data'] = DB::table('products')
        ->select('products.id as productId', 'products.title', 'products.description', 'products.price', 'products.photos as productPhotos')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->where('categories.name', '=', $name)
        ->limit($limit)
        ->get();

        $products['count'] = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->where('categories.name', '=', $name)
        ->get()
        ->count();

        return  $products;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($productId, $editTitle, $editDescription, $editPrice, $editCategory_id, $editPhotos, $editCondition)
    {

        $product = Product::find($productId);
        $product->title = $editTitle;
        $product->description = $editDescription;
        $product->price = $editPrice;
        $product->category_id = $editCategory_id;
        $product->condition = $editCondition;
        

        if($editPhotos != null){
            $photos = [];           
            foreach ($editPhotos as $id => $photo) {
                $extension = $photo->getClientOriginalExtension();
                $photos[] = $productId .'_'. $id .'.'. $extension;
                $photo->storeAs('public'.'/products', $productId.'_'.$id.'.'.$extension);
            }
    
            $product->photos = json_encode($photos);
        }

        $product->save();

    }

    public function add($editTitle, $editDescription, $editPrice, $editCategory_id, $editPhotos, $editCondition)
    {
        $lastProductId = Product::latest('id')->first();
        $newProductId = ($lastProductId->id) + 1;

        $product = new Product;
        $product->title = $editTitle;
        $product->description = $editDescription;
        $product->price = $editPrice;
        $product->category_id = $editCategory_id;
        $product->condition = $editCondition;
        
        if($editPhotos != null){
            $photos = [];           
            foreach ($editPhotos as $id => $photo) {
                $extension = $photo->getClientOriginalExtension();
                $photos[] = $newProductId .'_'. $id .'.'. $extension;
                $photo->storeAs('public'.'/products', $newProductId.'_'.$id.'.'.$extension);
            }
    
            $product->photos = json_encode($photos);
        } else{
            $product->photos = '[]';
        }

        $product->save();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


}
