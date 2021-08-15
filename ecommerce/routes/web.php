<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/search', [PageController::class, 'search'])->name('search');
Route::get('/product', [PageController::class, 'product'])->name('product');
Route::get('/cart', [PageController::class, 'cart'])->middleware(['auth', 'verified'])->name('cart');
Route::post('/cart/submit', [CartController::class, 'cartSubmit'])->name('cartSubmit');
Route::get('/category/{name}', [PageController::class, 'category'])->name('category');
Route::get('/cart/confirm', [PageController::class, 'cartConfirm'])->name('cartConfirm');

Route::get('/dashboard/{view?}', [PageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
