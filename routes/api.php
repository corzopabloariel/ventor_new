<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ventor\AjaxController;

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

Route::post('access', [AjaxController::class, 'access'])->name('ventor.ajax.access');
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