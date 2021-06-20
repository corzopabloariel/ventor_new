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

    $to = 'corzo.pabloariel@gmail.com';
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
        $html = 'Ocurrió un error. Revisar "/var/www/html/public/file/err_backup.txt"';
        $subject = 'Err: Backup';
        $email = \App\Models\Email::create([
            'use' => 0,
            'subject' => $subject,
            'body' => $html,
            'from' => config('app.mails.base'),
            'to' => $to
        ]);

        try {
            Mail::to($to)->send(new \App\Mail\BaseMail($subject, 'Backup', $html));
            $log = fopen("../public/file/err_backup.txt", "w") or die("Unable to open file!");
            fwrite($log, $th);
            fclose($log);
            $email->fill(["sent" => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(["error" => 1]);
            $email->save();
        }
    }

    try {

        $html = "";
        $html .= "<p>" . (new \App\Http\Controllers\Ventor\EmployeeController)->load(true) . "</p>";
        $html .= "<p>" . (new \App\Http\Controllers\Ventor\SellerController)->load(true) . "</p>";
        $html .= "<p>" . (new \App\Http\Controllers\TransportController)->load(true) . "</p>";
        $html .= "<p>" . (new \App\Http\Controllers\Ventor\ClientController)->load(true) . "</p>";
        $html .= "<p>" . (new \App\Http\Controllers\ProductController)->load(true) . "</p>";
        $subject = 'Update: OK';
        $email = \App\Models\Email::create([
            'use' => 0,
            'subject' => $subject,
            'body' => $html,
            'from' => config('app.mails.base'),
            'to' => $to
        ]);
        try {
            Mail::to($to)->send(new \App\Mail\BaseMail($subject, 'Actualizando', $html));
            $email->fill(["sent" => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(["error" => 1]);
            $email->save();
        }

    } catch (\Throwable $th) {
        $html = 'Ocurrió un error. Revisar "/var/www/html/public/file/err_update.txt"';
        $subject = 'Err: update';
        $email = \App\Models\Email::create([
            'use' => 0,
            'subject' => $subject,
            'body' => $html,
            'from' => config('app.mails.base'),
            'to' => $to
        ]);
        try {
            Mail::to($to)->send(new \App\Mail\BaseMail($subject, 'Actualizando', $html));
            $log = fopen("../public/file/err_update.txt", "w") or die("Unable to open file!");
            fwrite($log, $th);
            fclose($log);
            $email->fill(["sent" => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(["error" => 1]);
            $email->save();
        }

    }
});