<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Page\BasicController;
use App\Http\Controllers\Page\CartController;
use App\Http\Controllers\Page\ClientController;
use App\Http\Controllers\Page\FormController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('sitemap.php', function() {
    header ("Content-Type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    $url = "http://www.ventor.com.ar/";
    $fecha = date("Y-m-d");
    fila($url, $fecha, "monthly", "1.0");
    fila($url."empresa", $fecha, "monthly", "1.0");
    fila($url."descargas", $fecha, "monthly", "1.0");
    fila($url."productos", $fecha, "monthly", "1.0");
    fila($url."atencion/transmision", $fecha, "monthly", "1.0");
    fila($url."atencion/pagos", $fecha, "monthly", "1.0");
    fila($url."atencion/consulta", $fecha, "monthly", "1.0");
    fila($url."atencion/calidad", $fecha, "monthly", "1.0");
    fila($url."contacto", $fecha, "monthly", "1.0");
    foreach(\App\Models\Family::all() AS $f) {
        fila($url."parte:{$f->name_slug}", $fecha, "monthly", "0.8");
        foreach($f->subparts() AS $p)
            fila($url."parte:{$f->name_slug}/subparte:{$p["slug"]}", $fecha, "monthly", "0.8");
    }
    echo "</urlset>\n";
});
Route::get('sitemap_productos.php', function() {
    header ("Content-Type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    $url = "https://www.ventor.com.ar/";
    $fecha = date("Y-m-d");
    foreach(\App\Models\Product::all() AS $p)
        fila($url."producto:{$p->name_slug}", $fecha, "monthly", "0.8");
    echo "</urlset>\n";
});

// Que porquería esto no se hace
Route::get('naaaaaaaaaaaaaaaaaaa.php', function() {

    try {

        // Backup collections
        $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c products | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/products.json";
        shell_exec($backUpCommand);

        $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c orders | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/orders.json";
        shell_exec($backUpCommand);

        $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c clients | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/clients.json";
        shell_exec($backUpCommand);

        $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c emails | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/emails.json";
        shell_exec($backUpCommand);

    } catch (\Throwable $th) {

        Mail::to("corzo.pabloariel@gmail.com")
        ->send(
            new BaseMail(
                'Err: Backup',
                'Backup',
                'Ocurrió un error. Revisar "/var/www/html/public/file/log_err.txt"')
        );
        $log = fopen("public/file/log_err.txt", "w") or die("Unable to open file!");
        fwrite($log, $th);
        fclose($log);
    }

    try {

        $html = "";
        $html .= "<p>" . (new EmployeeController)->load(true) . "</p>";
        $html .= "<p>" . (new SellerController)->load(true) . "</p>";
        $html .= "<p>" . (new TransportController)->load(true) . "</p>";
        $html .= "<p>" . (new ClientController)->load(true) . "</p>";
        $html .= "<p>" . (new ProductController)->load(true) . "</p>";

        Mail::to("corzo.pabloariel@gmail.com")
        ->send(
            new BaseMail(
                'Update: OK',
                'Actualizando',
                $html)
        );
        $log = fopen("public/file/log_update.txt", "w") or die("Unable to open file!");
        fwrite($log, date("Y-m-d H:i:s"));
        fclose($log);

    } catch (\Throwable $th) {

        Mail::to("corzo.pabloariel@gmail.com")
        ->send(
            new BaseMail(
                'Err: update',
                'Actualizando',
                'Ocurrió un error. Revisar "/var/www/html/public/file/log_err2.txt"')
        );
        $log = fopen("public/file/log_err2.txt", "w") or die("Unable to open file!");
        fwrite($log, $th);
        fclose($log);

    }
});
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

Route::group(['middleware' => ['auth', 'role:usr,vnd,emp,adm']], function() {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
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