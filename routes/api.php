<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;

use App\Models\Part;
use App\Models\Subpart;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('products', ProductController::class);
*/
Route::get('products,{search?}', [ProductController::class, 'index_search'])->name('product.index');
Route::get('products__{brand?},{search?}', [ProductController::class, 'index'])->name('product.index');
Route::get('products__{brand?}', [ProductController::class, 'index'])->name('product.index');
Route::get('products', [ProductController::class, 'index'])->name('product.index');
Route::get('product/{product:name_slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('part:{part}__{brand?},{search?}', [ProductController::class, 'search_brand'])->name('part.index');
Route::get('part:{part}__{brand?}', [ProductController::class, 'search_brand'])->name('part.index');
Route::get('part:{part},{search?}', [ProductController::class, 'search'])->name('part.index');
Route::get('part:{part}', [ProductController::class, 'search_brand'])->name('part.index');
Route::get('part:{part}/subpart:{subpart:name_slug},{search}', [ProductController::class, 'search'])->name('subpart.index');
Route::get('part:{part}/subpart:{subpart:name_slug}__{brand?},{search?}', [ProductController::class, 'search_brand'])->name('subpart.index');
Route::get('part:{part}/subpart:{subpart:name_slug}__{brand?}', [ProductController::class, 'search_brand'])->name('subpart.index');
Route::get('part:{part}/subpart:{subpart:name_slug}', [ProductController::class, 'search_brand'])->name('subpart.index');
