<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Family;
use App\Models\Application;
use App\Models\Ventor\Ticket;
use Illuminate\Support\Facades\Validator;
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
            
        } else {

            $elements = Product::join('parts', 'products.part_id', '=', 'parts.id')
                ->join('subparts', 'products.subpart_id', '=', 'subparts.id')
                ->orderBy('parts.name')
                ->orderBy('subparts.code')
                ->paginate(PAGINATE);

        }
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['products']) || isset($permissions['products']) && !$permissions['products']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Productos']);
        }
        $buttons = [
            [
                "f" => "actualizar",
                "b" => "btn-primary",
                "i" => "fas fa-sync",
                "t" => "actualizar datos",
            ], [
                "f" => "file",
                "b" => "btn-dark",
                "i" => "fas fa-file-alt",
                "t" => "subir archivo TXT",
            ], [
                "f" => "categories",
                "b" => "btn-success",
                "i" => "fas fa-columns",
                "t" => "Categorías",
            ]
        ];
        if (!empty($permissions) && isset($permissions['products']) && !$permissions['products']['update']) {
            array_shift($buttons);
            array_shift($buttons);
        }
        $data = [
            "view" => "products",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/products"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Product::count(), 0, ",", "."),
            "entity" => "product",
            "placeholder" => "código, nombre, marca, modelo, parte, subparte",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "section" => "Productos",
            "buttons" => $buttons
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
        if (!\Auth::user()->isAdmin()) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Categorías']);
        }
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

    public function file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:txt'
        ]);
        if($validator->fails()){
            return response()->json([
                "error" => 1,
                "mssg" => "Extensión no válida."
            ], 200);
        }
        try {
            $file = $request->file;
            $path = env('FOLDER_TXT');
            $fileName = configs("FILE_PRODUCTS", env('FILE_PRODUCTS'));
            $file->move($path, "{$fileName}");
            Ticket::add(5, 0, null, 'Se subió el archivo: '.$fileName, [null, null, null]);
        } catch (\Exception $e) {
            return json_encode(["error" => 1, "msg" => "La excepción se creó en la línea: " . $e->getLine()]);
        }
        return json_encode(["success" => true, "update" => $request->has('update') ? 1 : 0, "error" => 0, "msg" => "Archivo subido exitosamente"]);
    }

    public function load(Bool $fromCron = false) {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['products']) || isset($permissions['products']) && !$permissions['products']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return Product::updateCollection($fromCron);

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

        return Part::order($request);

    }

    public function orderCategories(Request $request)
    {

        return Family::order($request);

    }

    public function application(Request $request)
    {

        return Application::updateCollection();

    }
}
