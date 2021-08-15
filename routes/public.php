<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\BasicController;
use App\Http\Controllers\Page\CartController;
use App\Http\Controllers\Page\ClientController;
use App\Http\Controllers\Page\FormController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', [BasicController::class, 'index']);
Route::get('{link?}', [BasicController::class, 'index'])
    ->where('link' , "index|empresa|novedades|descargas|calidad|trabaje|contacto|productos")
    ->name('index');

Route::get('webmail', function() {
    return \redirect('https://vps-1982038-x.dattaweb.com:2094/login.php');
});

Route::post('redirect', [BasicController::class, 'redirect'])
    ->name('redirect');
Route::get('track_download/{download}', [BasicController::class, 'track_download'])
    ->name('track_download')
    ->middleware(['auth', 'role:usr,vnd,emp,adm']);

Route::get('productos,{search}', [BasicController::class, 'part'])
    ->where('search', '.*')
    ->name('products_search');
Route::get('productos__{brand},{search}', [BasicController::class, 'part'])
    ->where('search', '.*')
    ->where('brand', '([a-z\-]+)?')
    ->name('products_brand_search');

Route::get('parte:{part}__{brand},{search}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('products_part_brand_search');
Route::get('parte:{part}__{brand}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->name('products_part_brand');
Route::get('parte:{part},{search}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('products_part_search');
Route::get('parte:{part}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->name('products_part');

Route::get('parte:{part}/subparte:{subpart}__{brand},{search}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('products_part_subpart_brand_search');
Route::get('parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->name('products_part_subpart_brand');
Route::get('parte:{part}/subparte:{subpart},{search}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->where('search', '.*')
    ->name('products_part_subpart_search');
Route::get('parte:{part}/subparte:{subpart}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('subpart', '([a-z\-]+)?')
    ->name('products_part_subpart');

Route::get('atencion/{section}', [BasicController::class, 'atencion'])
    ->where('section', 'transmision|pagos|consulta')
    ->name('client.atention');
Route::post('cliente/form:{section}', [FormController::class, 'client'])
    ->name('client.datos');

Route::match(['get', 'post'], 'eventSource', [ClientController::class, 'event'])->name('eventSource');
Route::post('browser', [ClientController::class, 'browser'])->name('client.browser');

Route::get('feed.{file}/{hash}/{ext}', [BasicController::class, 'feedFile'])->name('feed.files');

Route::group(['middleware' => ['auth', 'role:usr,vnd,emp,adm']], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('feed', [BasicController::class, 'feed'])->name('feed');
    Route::post('soap', [BasicController::class, 'soap'])->name('soap');
    Route::post('type', [BasicController::class, 'type'])->name('type');
    Route::post('data/{attr}', [BasicController::class, 'data'])->name('dataUser');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/show', [CartController::class, 'show'])->name('cart.show');
    Route::post('client/select', [CartController::class, 'client'])->name('client.select');
    Route::match(['get', 'post'], 'order/pdf', [CartController::class, 'pdf'])->name('order.pdf');
    Route::post('order/send', [CartController::class, 'send'])->name('order.send');
    Route::post('order/xls', [CartController::class, 'xls'])->name('order.xls');
    Route::match(['get', 'post'], 'pedido/confirm', [CartController::class, 'confirm'])
        ->name('order.success');
    Route::match(['get', 'post'], 'pedido/checkout', [CartController::class, 'checkout'])
        ->name('order.checkout');
    Route::match(['get', 'post'], 'pedido', [BasicController::class, 'order'])
        ->name('order');
    Route::match(['get', 'post'], 'pedido__{brand}', [BasicController::class, 'order'])
        ->name('order_brand');
    
    Route::get('{cliente_action}', [ClientController::class, 'action'])
        ->where('cliente_action', 'analisis-deuda|faltantes|comprobantes|mis-pedidos|mis-datos')
        ->name('client.action');

    Route::match(['get', 'post'], 'pedido/parte:{part}__{brand},{search}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_brand_search');
    Route::match(['get', 'post'], 'pedido/parte:{part}__{brand}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_part_brand');
    Route::match(['get', 'post'], 'pedido/parte:{part},{search}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_search');
    Route::match(['get', 'post'], 'pedido/parte:{part}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->name('order_part');

    Route::match(['get', 'post'], 'pedido/parte:{part}/subparte:{subpart}__{brand},{search}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_subpart_brand_search');
    Route::match(['get', 'post'], 'pedido/parte:{part}/subparte:{subpart},{search}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('search', '.*')
        ->name('order_part_subpart_search');
    Route::match(['get', 'post'], 'pedido/parte:{part}/subparte:{subpart}__{brand}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_part_subpart_brand');
    Route::match(['get', 'post'], 'pedido/parte:{part}/subparte:{subpart}', [BasicController::class, 'order'])
        ->where('part', '([a-z\-]+)?')
        ->where('subpart', '([a-z\-]+)?')
        ->name('order_part_subpart');

    Route::match(['get', 'post'], 'products__{brand},{search}', [BasicController::class, 'order'])
        ->where('search', '.*')
        ->where('brand', '([a-z\-]+)?')
        ->name('order_brand_search');
    Route::match(['get', 'post'], 'products,{search}', [BasicController::class, 'order'])
        ->where('search', '.*')
        ->name('order_search');
});

Route::get('producto:{product}', [BasicController::class, 'product'])
    ->where('product', '(.*)')
    ->name('product');