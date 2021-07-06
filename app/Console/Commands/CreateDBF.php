<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use XBase\TableReader;
use XBase\Enum\FieldType;
use XBase\Enum\TableType;
use XBase\Header\Column;
use XBase\Header\HeaderFactory;
use XBase\TableCreator;
use XBase\TableEditor;
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
        $arrMonth = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
        $date = date('d').' '.$arrMonth[date('n') - 1];
        $products = Product::orderBy('stmpdh_art', 'ASC')->limit(500)->get();
        $fileName = 'VENTOR LISTA DE PRECIOS FORMATO DBF '.$date.'.dbf';
        $filepath = public_path() . "/file/{$fileName}";
        if (file_exists($filepath))
            unlink($filepath);
        $header = HeaderFactory::create(TableType::DBASE_III_PLUS_MEMO);

        $tableCreator = new TableCreator($filepath, $header);
        $tableCreator
            ->addColumn(new Column([
                'name' => 'STMPDH_ART',
                'type' => FieldType::CHAR,
                'length' => 254,
            ]))
            ->addColumn(new Column([
                'name' => 'STMPDH_DES',
                'type' => FieldType::CHAR,
                'length' => 254
            ]))
            ->addColumn(new Column([
                'name' => 'PRECIO',
                'type' => FieldType::NUMERIC,
                'length' => 19,
                'decimalCount' => 5,
            ]))
            ->save();
        $table = new TableEditor($filepath, [ 'editMode' => TableEditor::EDIT_MODE_CLONE ]);
        foreach($products AS $product) {
            $parte = $product->subparte['name'] ?? '';
            if (empty($parte))
                $parte = $product->use;
            else
                $parte .= " ({$product->use})";
            $record = $table->appendRecord();
            $record->set('stmpdh_art', $product->stmpdh_art);
            $record->set('stmpdh_des', $parte);
            $record->set('precio', $product->precio);
            $table
                ->writeRecord()
                ->save();
        }
        $table->close();
        /////////////////
        $exports = public_path() . "/file/exports.txt";
        $fopen = fopen($exports, "a") or die("Unable to open file!");
        fwrite($fopen, "\n".$filepath);
        fclose($fopen);
    }
}
