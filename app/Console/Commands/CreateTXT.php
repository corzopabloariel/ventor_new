<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO TXT.txt';
        $file = public_path() . "/file/{$fileName}";
        if (Storage::disk('local')->exists("public/file/$fileName")) {

            Storage::delete("public/file/$fileName");

        }
        $products = Product::orderBy('stmpdh_art', 'ASC')->get();
        $data = view('exports.products.txt', [
            'products' => $products
        ])->render();
        Storage::disk('local')->put("public/file/$fileName", $data);
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".$file);
        fclose($fopen);
    }
}
