<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;
use App\Models\Email;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Ventor\EmployeeController;
use App\Http\Controllers\Ventor\SellerController;
use App\Http\Controllers\Ventor\ClientController;

class UpdateRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:day';

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
     * @return mixed
     */
    public function handle()
    {

        try {

            // Backup collections
            /*$backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c products | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/products.json";
            shell_exec($backUpCommand);

            $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c orders | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/orders.json";
            shell_exec($backUpCommand);

            $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c clients | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/clients.json";
            shell_exec($backUpCommand);

            $backUpCommand = "mongoexport --uri \"mongodb://AdminVentor:56485303@127.0.0.1:27017/ventor?authsource=admin\" -c emails | sed '/\"_id\":/s/\"_id\":[^,]*,//' > /home/vuserone/public_html/mongo/emails.json";
            shell_exec($backUpCommand);*/

        } catch (\Throwable $th) {

            $subject = 'Err: Backup';
            $body = 'Ocurrió un error. Revisar "/var/www/html/public/file/log_err.txt"';
            $to = 'corzo.pabloariel@gmail.com';
            $email = Email::create([
                'use' => 1,
                'subject' => $subject,
                'body' => $body,
                'from' => config('app.mails.base'),
                'to' => $to,
                'is_backup' => 1
            ]);
            try {
                Mail::to($to)
                ->send(
                    new BaseMail(
                        $subject,
                        'Backup',
                        $body)
                );
                $log = fopen("public/file/log_err.txt", "w") or die("Unable to open file!");
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
            $html .= "<p>" . (new EmployeeController)->load(true) . "</p>";
            /*$html .= "<p>" . (new SellerController)->load(true) . "</p>";
            $html .= "<p>" . (new TransportController)->load(true) . "</p>";
            $html .= "<p>" . (new ClientController)->load(true) . "</p>";
            $html .= "<p>" . (new ProductController)->load(true) . "</p>";*/

            $subject = 'Update: OK';
            $to = 'corzo.pabloariel@gmail.com';
            $email = Email::create([
                'use' => 1,
                'subject' => $subject,
                'body' => $html,
                'from' => config('app.mails.base'),
                'to' => $to,
                'is_update' => 1
            ]);

            try {
                Mail::to($to)
                ->send(
                    new BaseMail(
                        $subject,
                        'Actualizando',
                        $html)
                );
                $log = fopen("public/file/log_update.txt", "w") or die("Unable to open file!");
                fwrite($log, date("Y-m-d H:i:s"));
                fclose($log);
                $email->fill(["sent" => 1]);
                $email->save();
            } catch (\Throwable $th) {
                $email->fill(["error" => 1]);
                $email->save();
            }

        } catch (\Throwable $th) {
            $subject = 'Err: update';
            $to = 'corzo.pabloariel@gmail.com';
            $html = 'Ocurrió un error. Revisar "/var/www/html/public/file/log_err2.txt"';
            $email = Email::create([
                'use' => 1,
                'subject' => $subject,
                'body' => $html,
                'from' => config('app.mails.base'),
                'to' => $to,
                'is_update' => 1
            ]);
            try {
                Mail::to($to)
                ->send(
                    new BaseMail(
                        $subject,
                        'Actualizando',
                        $html)
                );
                $log = fopen("public/file/log_err2.txt", "w") or die("Unable to open file!");
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
