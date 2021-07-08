<?php

use Illuminate\Support\Facades\Route;


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
    \Artisan::call('update:data');
});

Route::get('maaaaaaaaaaaaaaaaaaa.php', function() {
    \Artisan::call('file:txt');
    \Artisan::call('file:xls');
    \Artisan::call('file:dbf');
});