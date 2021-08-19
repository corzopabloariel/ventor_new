<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\GeneralExportXLS;
use Excel;

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
        if (file_exists(public_path().'/file/'.$fileName))
            unlink(public_path().'/file/'.$fileName);

        // Creo el archivo
        Excel::store(new GeneralExportXLS, "public/file/$fileName", 'local');
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".public_path().'/file/'.$fileName);
        fclose($fopen);
    }
}
