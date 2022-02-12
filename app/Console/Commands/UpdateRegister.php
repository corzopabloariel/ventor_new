<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
    protected $description = 'Actualización de registros Transportes, Vendedores, Empleados y Clientes';

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

        (new \App\Http\Controllers\TransportController)->load(true);
        (new \App\Http\Controllers\Ventor\SellerController)->load(true);
        (new \App\Http\Controllers\Ventor\EmployeeController)->load(true);
        (new \App\Http\Controllers\Ventor\ClientController)->load(true);

    }
}
