<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CreateTXT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:txt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea archivo TXT de todos los productos';

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
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $arrMonth = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        $date = date('d').' '.$arrMonth[date('n') - 1];
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO TXT '.$date.'.txt';
        $file = public_path() . "/file/{$fileName}";
        if (file_exists($file))
            unlink($file);

        $products = Product::orderBy('stmpdh_art', 'ASC')->get();
        $data = view('exports.products.txt', [
            'products' => $products
        ])->render();

        $fopen = fopen($file, "w") or die("Unable to open file!");
        fwrite($fopen, $data);
        fclose($fopen);
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".$file);
        fclose($fopen);
    }
}
