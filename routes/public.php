<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\BasicController;

Route::get('{link?}', [BasicController::class, 'index'])
    ->where('link' , "index|empresa|descargas|calidad|trabaje|contacto|productos")
    ->name('index');

Route::post('redirect', [BasicController::class, 'redirect'])
    ->name('redirect');

Route::get('parte:{part}__{brand},{search}', [BasicController::class, 'part_brand'])
    ->where('part', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('part_brand_search');
Route::get('parte:{part}__{brand}', [BasicController::class, 'part_brand'])
    ->where('part', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->name('part_brand');
Route::get('parte:{part},{search}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('part_search');
Route::get('parte:{part}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->name('part');

Route::get('parte:{part}/subparte:{subpart}__{brand},{search}', [BasicController::class, 'subpart_brand'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('subpart_brand_search');
Route::get('parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'subpart_brand'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->name('subpart_brand');
Route::get('parte:{part}/subparte:{subpart},{search}', [BasicController::class, 'subpart'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('subpart_search');
Route::get('parte:{part}/subparte:{subpart}', [BasicController::class, 'subpart'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->name('subpart');

Route::get('search:{search},{brand}', [BasicController::class, 'products'])
    ->where('search', '.*')
    ->where('brand', '([a-z\-]+)?')
    ->name('products_brand_search');
Route::get('search:{search}', [BasicController::class, 'products'])
    ->where('search', '.*')
    ->name('products_search');

Route::get('{product}', [BasicController::class, 'product'])
    ->where('product', '!=', 'adm')
    ->name('product');

Route::group(['middleware' => ['auth', 'role:usr,vnd,emp,adm']], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('soap', [BasicController::class, 'soap'])->name('soap');
    Route::get('pedido', [BasicController::class, 'order'])
        ->name('order');
    Route::get('pedido__{brand}', [BasicController::class, 'brand'])
        ->name('order_brand');

    Route::get('pedido/parte:{part}__{brand},{search}', [BasicController::class, 'part_brand'])
        ->where('part', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_brand_search');
    Route::get('pedido/parte:{part}__{brand}', [BasicController::class, 'part_brand'])
        ->where('part', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_part_brand');
    Route::get('pedidio/parte:{part},{search}', [BasicController::class, 'part'])
        ->where('part', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_search');
    Route::get('pedido/parte:{part}', [BasicController::class, 'part'])
        ->where('part', '([a-z\-]+)?')
        ->name('order_part');

    Route::get('pedido/parte:{part}/subparte:{subpart}__{brand},{search}', [BasicController::class, 'subpart_brand'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_subpart_brand_search');
    Route::get('pedido/parte:{part}/subparte:{subpart},{search}', [BasicController::class, 'subpart'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_subpart_search');
    Route::get('pedido/parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'subpart_brand'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_subpart_brand');
    Route::get('pedido/parte:{part}/subparte:{subpart}', [BasicController::class, 'subpart'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->name('order_subpart');

    Route::get('pedido/search:{search},{brand}', [BasicController::class, 'products'])
        ->where('search', '.*')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_brand_search');
    Route::get('pedido/search:{search}', [BasicController::class, 'products'])
        ->where('search', '.*')
        ->name('order_search');
});