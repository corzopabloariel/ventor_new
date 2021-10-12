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

Route::post('products', [AjaxController::class, 'products'])->name('ventor.ajax.products');
Route::post('paginator', [AjaxController::class, 'paginator'])->name('ventor.ajax.paginator');