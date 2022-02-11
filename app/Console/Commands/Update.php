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
    protected $description = 'Actualización de Productos';

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
        $data = (new \App\Http\Controllers\ProductController)->load(true);
        if (!$data['error']) {

            \Artisan::call('file:txt');
            \Artisan::call('file:csv');
            \Artisan::call('file:dbf');
            \Artisan::call('file:xls');

        }
    }
}
