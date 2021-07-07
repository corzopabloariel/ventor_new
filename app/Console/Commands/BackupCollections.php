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
    protected $description = 'Ejecuta otros commands que son necesarios para crear el txt, dbf y xls';

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

        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, "https://ventor.com.ar/maaaaaaaaaaaaaaaaaaa.php");
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $phoneList = curl_exec($cURLConnection);
        curl_close($cURLConnection);

    }
}
