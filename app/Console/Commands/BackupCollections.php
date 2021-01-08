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
            $backUpCommand = "mongodump --archive='/var/backups/mongobackups/products-{$date}-db' --db=ventor --collection=products";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongodump --archive='/var/backups/mongobackups/orders-{$date}-db' --db=ventor --collection=orders";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongodump --archive='/var/backups/mongobackups/clients-{$date}-db' --db=ventor --collection=clients";
            shell_exec($backUpCommand);
    
            $backUpCommand = "mongodump --archive='/var/backups/mongobackups/emails-{$date}-db' --db=ventor --collection=emails";
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
