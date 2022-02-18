<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\BasicController;
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

Route::get('productos,{search}', [BasicController::class, 'part'])
    ->where('search', '.*')
    ->name('products_search');
Route::get('productos__{brand}', [BasicController::class, 'part'])
    ->where('brand', '([a-z\-]+)?')
    ->name('products_brand');
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
Route::get('parte:{part}__{brand}', [BasicController::class, 'part'])
    ->where('part', '([a-z\-]+)?')
    ->where('brand', '([a-z\-]+)?')
    ->name('products_part_brand');
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

Route::get('feed.{file}/{hash}/{ext}', [BasicController::class, 'feedFile'])->name('feed.files');
Route::get('file/{pathToFile}', function($pathToFile) {
    if (auth()->guard('web')->check()) {
        if (file_exists(storage_path().'/app/public/file/'.$pathToFile)) {
            $document = storage_path().'/app/public/file/'.$pathToFile;
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($document).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($document));
            readfile($document);
            die;
        }
        abort(404);
    } else {
        abort(403);
    }
});
Route::group(['middleware' => ['auth', 'role:usr,vnd,emp,adm']], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('feed', [BasicController::class, 'feed'])->name('feed');
    Route::post('descargas', [BasicController::class, 'descargas'])->name('descargas');
    Route::post('data', [BasicController::class, 'data'])->name('dataUser');

    Route::get('{link}', [BasicController::class, 'index'])
        ->where('link' , "aplicacion")
        ->name('index.application');
    Route::post('aplicacion', [BasicController::class, 'application'])
        ->name('application.products');
    Route::get('aplicacion{data}', [BasicController::class, 'application'])
        ->where('data', '.*')
        ->name('application.search');
    Route::get('application{data}', [BasicController::class, 'application'])
        ->where('data', '.*')
        ->name('application.brand');
    
    Route::get('{cliente_action}', [BasicController::class, 'client'])
        ->where('cliente_action', 'analisis-deuda|faltantes|comprobantes|mis-pedidos|mis-datos')
        ->name('client.action');
});

Route::get('producto:{product}', [BasicController::class, 'product'])
    ->where('product', '(.*)')
    ->name('product');