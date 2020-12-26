<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\BasicController;

Route::get('{link?}', [BasicController::class, 'index'])
    ->where('link' , "index|empresa|descargas|calidad|trabaje|contacto|productos")
    ->name('index');

Route::post('redirect', [BasicController::class, 'redirect'])
    ->name('redirect');

Route::get('parte:{part}__{brand}', [BasicController::class, 'part_brand'])
    ->name('part_brand');
Route::get('parte:{part}', [BasicController::class, 'part'])
    ->name('part');

Route::get('parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'subpart_brand'])
    ->name('subpart_brand');
Route::get('parte:{part}/subparte:{subpart}', [BasicController::class, 'subpart'])
    ->name('subpart');

Route::get('{product}', [BasicController::class, 'product'])
    ->where('product', '!=', 'pedido')
    ->name('product');

Route::group(['middleware' => ['auth', 'role:usr,vnd,emp,adm']], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('pedido', [BasicController::class, 'order'])
        ->name('order');
    Route::get('pedido__{brand}', [BasicController::class, 'order_brand'])
        ->name('order_brand');

    Route::get('pedido/parte:{part}__{brand}', [BasicController::class, 'order_part_brand'])
        ->name('order_part_brand');
    Route::get('pedido/parte:{part}', [BasicController::class, 'order_part'])
        ->name('order_part');

    Route::get('pedido/parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'order_subpart_brand'])
        ->name('order_subpart_brand');
    Route::get('pedido/parte:{part}/subparte:{subpart}', [BasicController::class, 'order_subpart'])
        ->name('order_subpart');
});