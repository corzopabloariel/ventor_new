<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\GeneralExportCSV;
use Excel;
use Illuminate\Support\Facades\Storage;

class CreateCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea archivo CSV de todos los productos';

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
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO CSV.csv';
        if (Storage::disk('local')->exists("public/file/$fileName")) {

            Storage::delete("public/file/$fileName");

        }
        // Creo el archivo
        Excel::store(new GeneralExportCSV, "public/file/$fileName", 'local', \Maatwebsite\Excel\Excel::CSV);
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".public_path().'/file/'.$fileName);
        fclose($fopen);
    }
}
