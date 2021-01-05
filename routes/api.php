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

/*Route::middleware()->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('products', ProductController::class);
*/
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('products__{brand},{search}', [ProductController::class, 'index_brand_search'])->name('product.brand_search');
    Route::get('products,{search}', [ProductController::class, 'index_search'])->name('product.search');
    Route::get('products__{brand}', [ProductController::class, 'index_brand'])->name('product.brand');
    Route::get('products', [ProductController::class, 'index'])->name('product.index');
    Route::get('product/{product:name_slug}', [ProductController::class, 'show'])->name('product.show');

    Route::get('part:{part:name_slug}__{brand},{search}', [ProductController::class, 'part_brand_search'])->name('part.brand_search');
    Route::get('part:{part:name_slug},{search}', [ProductController::class, 'part_search'])->name('part.search');
    Route::get('part:{part:name_slug}__{brand}', [ProductController::class, 'part_brand'])->name('part.brand');
    Route::get('part:{part:name_slug}', [ProductController::class, 'part'])->name('part.index');

    Route::get('part:{part:name_slug}/subpart:{subpart:name_slug}__{brand},{search}', [ProductController::class, 'subpart_brand_search'])->name('subpart.brand_search');
    Route::get('part:{part:name_slug}/subpart:{subpart:name_slug},{search}', [ProductController::class, 'subpart_search'])->name('subpart.search');
    Route::get('part:{part:name_slug}/subpart:{subpart:name_slug}__{brand}', [ProductController::class, 'subpart_brand'])->name('subpart.brand');
    Route::get('part:{part:name_slug}/subpart:{subpart:name_slug}', [ProductController::class, 'subpart'])->name('subpart.index');
});