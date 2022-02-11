<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\GeneralExportXLS;
use Excel;
use Illuminate\Support\Facades\Storage;

class CreateXLS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:xls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea archivo XLS de todos los productos';

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
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO XLS.xls';
        if (Storage::disk('local')->exists("public/file/$fileName")) {

            Storage::delete("public/file/$fileName");

        }
        // Creo el archivo
        Excel::store(new GeneralExportXLS, "public/file/$fileName", 'local');
    }
}
