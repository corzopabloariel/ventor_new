<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Part;
use App\Models\Family;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $elements = Transport::where("code", "LIKE", "%{$request->search}%")->
                orWhere("description", "LIKE", "%{$request->search}%")->
                orWhere("address", "LIKE", "%{$request->search}%")->
                orWhere("phone", "LIKE", "%{$request->search}%")->
                orWhere("person", "LIKE", "%{$request->search}%")->
                orderBy("code")->paginate(PAGINATE);

        } else
            $elements = Product::orderBy("parte")->orderBy("subparte.code", "ASC")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/products"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Product::count(), 0, ",", "."),
            "entity" => "product",
            "placeholder" => "código, nombre, marca, modelo, parte, subparte",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "section" => "Productos",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ], [
                    "f" => "categories",
                    "b" => "btn-success",
                    "i" => "fas fa-columns",
                    "t" => "Categorías",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ["code", "description", "address", "phone", "person"];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }

    public function category(Request $request)
    {
        if (isset($request->search)) {
            $elements = Family::where("name", "LIKE", "%{$request->search}%")->
                orderBy("order")->paginate(PAGINATE);

        } else
            $elements = Family::orderBy("order")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/products/categories"),
            "breadcrumb" => [
                ["href" => \URL::to(\Auth::user()->redirect() . "/products"), "name" => "Productos"]
            ],
            "elements" => $elements,
            "entity" => "family",
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Family::count(), 0, ",", "."),
            "placeholder" => "nombre",
            "section" => "Categorías",
            "help" => "Categorías con íconos para la web",
            "buttons" => [
                [
                    "f" => "order",
                    "b" => "btn-primary",
                    "i" => "fas fa-sort",
                    "t" => "ordenar",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ["name"];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
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
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Documentos insertados: " . Product::count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        return response()->json([
            "error" => 1,
            "txt" => "Archivo no encontrado"
        ], 410);
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
