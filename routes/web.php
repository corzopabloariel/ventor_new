<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\BasicController;
use App\Http\Controllers\Ventor\HomeController;
use App\Http\Controllers\Ventor\EmployeeController;
use App\Http\Controllers\Ventor\SellerController;
use App\Http\Controllers\Ventor\SliderController;
use App\Http\Controllers\Ventor\ClientController;
use App\Http\Controllers\Ventor\DownloadController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\NumberController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/{role}', [LoginController::class, 'showLoginForm'])
    ->where('role', 'emp|vnd|client|adm')
    ->name('login');
Route::post('login/{role}', [LoginController::class, 'login'])
    ->where('role', 'emp|vnd|client|adm')
    ->name('login');

Route::group(['middleware' => ['auth', 'role:adm'], 'prefix' => 'adm'], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::match(['post', 'get'], '/', [HomeController::class, 'index'])->name('adm');
    Route::delete('file', [BasicController::class, 'deleteFile'])->name('deleteFile');
    Route::post('edit', [BasicController::class, 'edit'])->name('edit');
    Route::post('history', [HomeController::class, 'history'])->name('history');
    Route::get('update', [BasicController::class, 'update'])->name('update.index');
    Route::match(['post', 'get'], 'data', [HomeController::class, 'data'])->name('data');

    Route::match(['post', 'get'], 'content/{section}', [HomeController::class, 'content'])
        ->where('section', 'calidad|empresa')
        ->name('ventor.slider.index');
    Route::get('orders', [HomeController::class, 'orders'])->name('order.index');
    Route::post('order/{order}', [HomeController::class, 'order'])->name('order.index');
    /**********************************
            SLIDERS
     ********************************** */
    Route::get('sliders/{section}', [SliderController::class, 'index'])
        ->where('section', 'home|empresa')
        ->name('ventor.slider.index');
    Route::get('sliders/edit', [SliderController::class, 'edit'])->name('ventor.slider.edit');
    Route::get('sliders/{slider}', [SliderController::class, 'show'])->name('ventor.slider.show');
    Route::post('sliders', [SliderController::class, 'store'])->name('ventor.slider.store');
    Route::post('sliders/{slider}', [SliderController::class, 'update'])->name('ventor.slider.update');
    Route::delete('sliders/{slider}', [SliderController::class, 'destroy'])->name('ventor.slider.destroy');

    Route::get('texts', [TextController::class, 'index'])->name('ventor.text.index');
    Route::get('texts/edit', [TextController::class, 'edit'])->name('ventor.text.edit');
    Route::get('texts/{text}', [TextController::class, 'show'])->name('ventor.text.show');
    Route::post('texts', [TextController::class, 'store'])->name('ventor.text.store');
    Route::post('texts/{text}', [TextController::class, 'update'])->name('ventor.text.update');
    Route::delete('texts/{text}', [TextController::class, 'destroy'])->name('ventor.text.destroy');

    Route::get('configs', [ConfigController::class, 'index'])->name('ventor.config.index');
    Route::get('configs/edit', [ConfigController::class, 'edit'])->name('ventor.config.edit');
    Route::get('configs/{config}', [ConfigController::class, 'show'])->name('ventor.config.show');
    Route::post('configs', [ConfigController::class, 'store'])->name('ventor.config.store');
    Route::post('configs/{config}', [ConfigController::class, 'update'])->name('ventor.config.update');
    Route::delete('configs/{config}', [ConfigController::class, 'destroy'])->name('ventor.config.destroy');

    Route::get('news', [NewController::class, 'index'])->name('ventor.new.index');
    Route::get('news/edit', [NewController::class, 'edit'])->name('ventor.new.edit');
    Route::get('news/{newness}', [NewController::class, 'show'])
        ->where('newness', '[0-9]+')
        ->name('ventor.new.show');
    Route::post('news', [NewController::class, 'store'])->name('ventor.new.store');
    Route::post('news/{newness}', [NewController::class, 'update'])
        ->where('newness', '[0-9]+')
        ->name('ventor.new.update');
    Route::delete('news/{newness}', [NewController::class, 'destroy'])
        ->where('newness', '[0-9]+')
        ->name('ventor.new.destroy');
    Route::post('news/order', [NewController::class, 'order'])->name('ventor.new.order');

    Route::get('numbers', [NumberController::class, 'index'])->name('ventor.number.index');
    Route::get('numbers/edit', [NumberController::class, 'edit'])->name('ventor.number.edit');
    Route::get('numbers/{number}', [NumberController::class, 'show'])
        ->where('number', '[0-9]+')
        ->name('ventor.number.show');
    Route::post('numbers', [NumberController::class, 'store'])->name('ventor.number.store');
    Route::post('numbers/{number}', [NumberController::class, 'update'])
        ->where('number', '[0-9]+')
        ->name('ventor.number.update');
    Route::delete('numbers/{number}', [NumberController::class, 'destroy'])->name('ventor.number.destroy');
    Route::post('numbers/order', [NumberController::class, 'order'])->name('ventor.number.order');

    Route::get('downloads', [DownloadController::class, 'index'])->name('ventor.download.index');
    Route::get('downloads/edit', [DownloadController::class, 'edit'])->name('ventor.download.edit');
    Route::get('downloads/{download}', [DownloadController::class, 'show'])
        ->where('download', '[0-9]+')
        ->name('ventor.download.show');
    Route::post('downloads', [DownloadController::class, 'store'])->name('ventor.download.store');
    Route::post('downloads/{download}', [DownloadController::class, 'update'])
        ->where('download', '[0-9]+')
        ->name('ventor.download.update');
    Route::delete('downloads/{download}', [DownloadController::class, 'destroy'])->name('ventor.download.destroy');
    Route::post('downwloads/categories', [DownloadController::class, 'orderCategories'])->name('ventor.category.order');
    Route::post('downloads/order', [DownloadController::class, 'order'])->name('ventor.download.order');

    Route::get('transports', [TransportController::class, 'index'])->name('ventor.transport.index');
    Route::get('transports/load', [TransportController::class, 'load'])->name('ventor.transport.load');

    Route::get('products', [ProductController::class, 'index'])->name('ventor.product.index');
    Route::get('products/load', [ProductController::class, 'load'])->name('ventor.product.load');
    Route::get('products/categories', [ProductController::class, 'category'])->name('ventor.product.categories');
    Route::post('products/categories/order', [ProductController::class, 'orderCategories'])->name('ventor.product.category.order');
    Route::post('products/categories/part', [ProductController::class, 'partCategories'])->name('ventor.product.category.part');

    Route::get('families/edit', [ProductController::class, 'edit'])->name('ventor.family.edit');
    Route::get('families/{family}', [ProductController::class, 'show'])->name('ventor.family.show');
    Route::post('families', [ProductController::class, 'store'])->name('ventor.family.store');
    Route::post('families/{family}', [ProductController::class, 'update'])->name('ventor.family.update');
    Route::delete('families/{family}', [ProductController::class, 'destroy'])->name('ventor.family.destroy');

    Route::get('employees', [EmployeeController::class, 'index'])->name('ventor.employee.index');
    Route::get('employees/load', [EmployeeController::class, 'load'])->name('ventor.employee.load');
    Route::get('employees/list', [EmployeeController::class, 'list'])->name('ventor.employee.list');
    Route::post('employees/role', [EmployeeController::class, 'role'])->name('ventor.employee.role');
    Route::get('users', [EmployeeController::class, 'users'])->name('ventor.employee.user');

    Route::get('sellers', [SellerController::class, 'index'])->name('ventor.seller.index');
    Route::get('sellers/load', [SellerController::class, 'load'])->name('ventor.seller.load');

    Route::get('clients', [ClientController::class, 'index'])->name('ventor.client.index');
    Route::get('clients/load', [ClientController::class, 'load'])->name('ventor.client.load');
    Route::post('clients/{clientID}', [ClientController::class, 'pass'])->name('ventor.client.pass');
});