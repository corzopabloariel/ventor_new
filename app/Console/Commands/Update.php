<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualización de registros TXT de la BD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
                $log = fopen(public_path()."/file/err_backup.txt", "w") or die("Unable to open file!");
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
                $log = fopen(public_path()."/file/log_update.txt", "w") or die("Unable to open file!");
                fwrite($log, date("Y-m-d H:i:s"));
                fclose($log);
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
                $log = fopen(public_path()."/file/err_update.txt", "w") or die("Unable to open file!");
                fwrite($log, $th);
                fclose($log);
                $email->fill(["sent" => 1]);
                $email->save();
            } catch (\Throwable $th) {
                $email->fill(["error" => 1]);
                $email->save();
            }

        }
    }
}
