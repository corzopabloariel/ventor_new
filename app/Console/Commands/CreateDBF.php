<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use org\majkel\dbase\Builder;
use org\majkel\dbase\Format;
use org\majkel\dbase\Field;
use App\Models\Product;

class CreateDBF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:dbf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $products = Product::orderBy('stmpdh_art', 'ASC')->get();
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO DBF.dbf';
        $filePath = storage_path()."/app/public/file/{$fileName}";
        if (file_exists($filePath))
            unlink($filePath);

        $table = Builder::create()
            ->setFormatType(Format::DBASE3)
            ->addField(Field::create(Field::TYPE_CHARACTER)->setName('STMPDH_ART')->setLength(30))
            ->addField(Field::create(Field::TYPE_CHARACTER)->setName('STMPDH_DES')->setLength(120))
            ->addField(Field::create(Field::TYPE_NUMERIC)->setName('PRECIO')->setLength(20)->setDecimalCount(5))
            ->build($filePath);

        foreach($products AS $product) {
            $table->insert([
                'STMPDH_ART' => $product->stmpdh_art,
                'STMPDH_DES' => $product->stmpdh_tex,
                'PRECIO' => $product->precio,
            ]);
        }
        
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".$filePath);
        fclose($fopen);
    }
}
