<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Part;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     *
     * @param  String $row
     * @return String
     */
    public function clearRow($row)
    {
        $value = utf8_encode(trim($row));
        return $value === "" ? NULL : $value;
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function load(Request $request)
    {
        set_time_limit(0);
        $model = new Product();
        $property = $model->getFillable();
        $arr_err = [];
        $filename = implode('/', [public_path(), env('FOLDER_TXT'), env('FILE_PRODUCTS')]);
        if (file_exists($filename))
        {
            Product::removeAll();
            $file = fopen($filename, 'r');
            while (!feof($file))
            {
                $row = trim(fgets($file));
                if (empty($row) || strpos($row, 'STMPDH_ARTCOD') !== false)
                {
                    continue;
                }
                $aux = explode(env('SEPARATOR'), $row);
                $aux = array_map('self::clearRow', $aux);
                if (empty($aux))
                    continue;
                try {
                    $data = array_combine($property, $aux);
                    $data["cantminvta"] = floatval(str_replace("," , ".", $data["cantminvta"]));
                    $data["usr_stmpdh"] = floatval(str_replace("," , ".", $data["usr_stmpdh"]));
                    $data["precio"] = floatval(str_replace("," , ".", $data["precio"]));
                    $data["stock_mini"] = intval($data["stock_mini"]);
                    if (strpos($data["fecha_ingr"], " ") !== false)
                    {
                        $auxDate = explode(" ", $data["fecha_ingr"]);
                        list($d, $m, $a) = explode("/", $auxDate[0]);
                        $data["fecha_ingr"] = date("Y-m-d H:i:s" , strtotime("{$a}/{$m}/{$d} {$auxDate[1]}"));
                    } else {
                        list($d, $m, $a) = explode("/", $data["fecha_ingr"]);
                        $data["fecha_ingr"] = date("Y-m-d", strtotime("{$a}/{$m}/{$d}"));
                    }
                    $product = Product::create($data);
                    $part = Part::firstOrNew(
                        ['name' => $data['parte']]
                    );
                    $part->save();
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            fclose($file);
            dd($arr_err, Product::count());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
