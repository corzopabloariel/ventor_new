<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Family;
use App\Models\Ventor\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $elements = Product::where("stmpdh_art", "LIKE", "%{$request->search}%")->
                orWhere("stmpdh_tex", "LIKE", "%{$request->search}%")->
                orWhere("web_marcas", "LIKE", "%{$request->search}%")->
                orWhere("modelo_anio", "LIKE", "%{$request->search}%")->
                orWhere("subparte.code", "LIKE", "%{$request->search}%")->
                orWhere("subparte.name", "LIKE", "%{$request->search}%")->
                orderBy("parte")->orderBy("subparte.code", "ASC")->paginate(PAGINATE);
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
            $data["searchIn"] = ["stmpdh_art", "stmpdh_tex", "web_marcas", "modelo_anio", "parte", "subparte"];
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
            "families" => Family::orderBy('order')->get(),
            "parts" => Part::all(),
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
                ], [
                    "f" => "parts",
                    "b" => "btn-warning",
                    "i" => "fas fa-vote-yea",
                    "t" => "partes",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Family);
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
            Subpart::removeAll();
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
                    $subpart = Subpart::where("code", $product->subparte["code"])->first();
                    if (!$subpart) {
                        Subpart::create([
                            "code" => $product->subparte["code"],
                            "name" => $product->subparte["name"],
                            "name_slug" => Str::slug($product->subparte["name"], "-"),
                            "family_id" => $part->family_id,
                            "part_id" => $part->id
                        ]);
                    }
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
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function show(Family $family)
    {
        return $family;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Family $family)
    {
        return $family;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Family $family)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $family, new Family);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($family, new Family);
    }

    ////////////////

    public function partCategories(Request $request)
    {
        for($i = 0; $i < count($request->part); $i++)
        {
            $part = Part::find($request->part[$i]);
            $valueNew = empty($request->family[$i]) ? null : $request->family[$i];
            $valueOld = $part->family_id;
            $part->fill(["family_id" => $valueNew]);
            $part->save();
            if ($valueOld != $valueNew) {
                Ticket::create([
                    'type' => 3,
                    'table' => 'parts',
                    'table_id' => $part->id,
                    'obs' => '<p>Se modificó el valor de "family_id" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                    'user_id' => \Auth::user()->id
                ]);
                collect($part->subparts())->each(function ($item, $key) use ($valueOld, $valueNew) {
                    $item->fill(["family_id" => $valueNew]);
                    $item->save();
                    Ticket::create([
                        'type' => 3,
                        'table' => 'subparts',
                        'table_id' => $item->id,
                        'obs' => '<p>Se modificó el valor de "family_id" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                        'user_id' => \Auth::user()->id
                    ]);
                });
            }
        }

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Categorías modificadas"
        ], 200);
    }

    public function orderCategories(Request $request)
    {
        for($i = 0; $i < count($request->family); $i++) {
            $family = Family::find($request->family[$i]);
            $valueNew = $i;
            $valueOld = $family->order;
            $family->fill(["order" => $i]);
            $family->save();
            if ($valueOld != $valueNew) {
                Ticket::create([
                    'type' => 3,
                    'table' => 'families',
                    'table_id' => $family->id,
                    'obs' => '<p>Se modificó el valor de "order" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }
        }

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Orden guardado"
        ], 200);
    }
}
