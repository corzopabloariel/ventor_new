<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ventor\AjaxController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\MailController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SubPartController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('clientAction', [AjaxController::class, 'clientAction'])->name('ventor.ajax.clientAction');
Route::post('applications', [AjaxController::class, 'applications'])->name('ventor.ajax.applications');
Route::post('products', [AjaxController::class, 'products'])->name('ventor.ajax.products');
Route::post('products/brands', [AjaxController::class, 'productsBrands'])->name('ventor.ajax.products.brands');
Route::post('paginator', [AjaxController::class, 'paginator'])->name('ventor.ajax.paginator');
Route::post('prices', [AjaxController::class, 'prices'])->name('ventor.ajax.prices');
Route::post('stock', [AjaxController::class, 'stock'])->name('ventor.ajax.stock');
Route::post('markup', [AjaxController::class, 'markup'])->name('ventor.ajax.markup');
Route::post('pdf', [AjaxController::class, 'pdf'])->name('ventor.ajax.pdf');

Route::post('cart.products', [AjaxController::class, 'cartProducts'])->name('ventor.ajax.cart.products');
Route::post('mail', [AjaxController::class, 'mail'])->name('ventor.ajax.mail');
Route::post('order.new', [AjaxController::class, 'orderNew'])->name('ventor.ajax.order.new');
Route::post('order.pdf/{order}', [AjaxController::class, 'orderPdf'])->name('ventor.ajax.order.pdf');

Route::post('clients', [AjaxController::class, 'clients'])->name('ventor.ajax.clients');
Route::post('client', [AjaxController::class, 'client'])->name('ventor.ajax.client');
Route::post('transports', [AjaxController::class, 'transports'])->name('ventor.ajax.transports');

Route::group(['prefix' => 'v2.0'], function() {
    Route::group(['prefix' => 'products'], function() {
        Route::get('part:{part}/subpart:{subpart:name_slug}__{brand},{search}', [SubPartController::class, 'brand_search'])->name('api.subpart.brand_search');
        Route::get('part:{part}/subpart:{subpart:name_slug},{search}', [SubPartController::class, 'search'])->name('api.subpart.search');
        Route::get('part:{part}/subpart:{subpart:name_slug}__{brand}', [SubPartController::class, 'brand'])->name('api.subpart.brand');
        Route::get('part:{part}/subpart:{subpart:name_slug}', [SubPartController::class, 'index'])->name('api.subpart.index');
    
        Route::get('part:{part:name_slug}__{brand},{search}', [PartController::class, 'brand_search'])->name('api.part.brand_search');
        Route::get('part:{part:name_slug},{search}', [PartController::class, 'search'])->name('api.part.search');
        Route::get('part:{part:name_slug}__{brand}', [PartController::class, 'brand'])->name('api.part.brand');
        Route::get('part:{part:name_slug}', [PartController::class, 'index'])->name('api.part.index');
    });
    Route::post('applications/elements', [ApplicationController::class, 'elements'])->name('api.application.elements');
    Route::apiResource('applications', ApplicationController::class);
    Route::put('products/price', [ProductController::class, 'price'])->name('api.products.price');
    Route::put('products/stock', [ProductController::class, 'stock'])->name('api.products.stock');
    Route::put('products/brands', [ProductController::class, 'brands'])->name('api.products.brands');
    Route::resource('products', ProductController::class)->except([
        'index',
        'show'
    ]);
    Route::put('products', [ProductController::class, 'index'])->name('api.products.index');
    Route::patch('products', [ProductController::class, 'show'])->name('api.products.show');

    Route::get('carts/{user}/attributes', [CartController::class, 'attributes'])->name('api.carts.attributes');
    Route::get('carts/{user}/products/{type}', [CartController::class, 'products'])->name('api.carts.products');
    Route::delete('carts/{user}', [CartController::class, 'destroy'])->name('api.carts.destroy');
    Route::get('order', [OrderController::class, 'index'])->name('api.order.index');
    Route::post('order', [OrderController::class, 'store'])->name('api.order.store');
    Route::get('order/{order}', [OrderController::class, 'show'])->name('api.order.show');
    Route::apiResource('carts', CartController::class);
    Route::apiResource('clients', ClientController::class)->except([
        'update'
    ]);
    Route::put('clients', [ClientController::class, 'update'])->name('api.clients.update');
    Route::get('clients/{client}/{action}', [ClientController::class, 'action'])->name('api.clients.action');
    Route::apiResource('users', UserController::class)->except([
        'index'
    ]);
    Route::get('users/{user}/seller', [UserController::class, 'seller'])->name('api.users.seller');

    Route::post('mail', [MailController::class, 'index'])->name('api.mail.index');
});