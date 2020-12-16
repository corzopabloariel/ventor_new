<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\BasicController;
use App\Http\Controllers\Ventor\HomeController;
use App\Http\Controllers\Ventor\EmployeeController;
use App\Http\Controllers\Ventor\SellerController;
use App\Http\Controllers\Ventor\SliderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransportController;

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

Route::get('/', [SliderController::class, 'create']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/{role}', [LoginController::class, 'showLoginForm'])
    ->where('role', 'emp|vnd|client|adm');
Route::post('login/{role}', [LoginController::class, 'login'])
    ->where('role', 'emp|vnd|client|adm')
    ->name('login');
Route::group(['middleware' => ['auth', 'role:adm'], 'prefix' => 'adm'], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('adm');
    Route::delete('file', [BasicController::class, 'deleteFile'])->name('deleteFile');
    Route::post('edit', [BasicController::class, 'edit'])->name('edit');
    Route::get('update', [BasicController::class, 'update'])->name('update.index');

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

    Route::get('transports', [TransportController::class, 'index'])->name('ventor.transport.index');
    Route::get('transports/load', [TransportController::class, 'load'])->name('ventor.transport.load');
});