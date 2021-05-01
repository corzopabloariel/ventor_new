<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;

class BackupCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup de colecciones';

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
        $date = date("ymdhi");

        try {
            $backUpCommand = "mongoexport -d ventor -c products -o '/var/backups/mongobackups/products-{$date}-db.json' --type json";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongoexport -d ventor -c orders -o '/var/backups/mongobackups/orders-{$date}-db.json' --type json";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongoexport -d ventor -c clients -o '/var/backups/mongobackups/clients-{$date}-db.json' --type json";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongoexport -d ventor -c emails -o '/var/backups/mongobackups/emails-{$date}-db.json' --type json";
            shell_exec($backUpCommand);

            $html = "<p style='text-align:center'>4 backups OK</p>";
        } catch (\Throwable $th) {
            $html = $th;
        }

        Mail::to("corzo.pabloariel@gmail.com")
        ->send(
            new BaseMail(
                "comando activo",
                'Actualizando',
                $html)
        );
    }
}
