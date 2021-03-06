<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Ventor\Download;
use App\Models\Ventor\Ticket;
use App\Models\Content;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['downloads']) || isset($permissions['downloads']) && !$permissions['downloads']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Descargas']);
        }
        if (isset($request->search)) {
            $elements = Download::where("name", "LIKE", "%{$request->search}%")->
                orderBy('order')->
                paginate(PAGINATE);
        } else
            $elements = Download::orderBy('order')->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/downloads"),
            "elements" => $elements,
            "entity" => "download",
            "placeholder" => "nombre",
            "section" => "Descargas",
            "buttons" => [
                [
                    "f" => "order",
                    "b" => "btn-primary",
                    "i" => "fas fa-sort",
                    "t" => "ordernar Descargas",
                ], [
                    "f" => "orderCategories",
                    "b" => "btn-success",
                    "i" => "fas fa-sort",
                    "t" => "ordernar Categorías",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ],
            "categoriesDATA" => Content::section("categoriesDownload")->data,
            "categories" => [
                'PUBL' => 'Descargas e instructivos',
                'CATA' => 'Catálogo (Privada)',
                'PREC' => 'Listas de precios (Privada)',
                'OTRA' => 'Otra'
            ],
            "all" => Download::orderBy("type")->orderBy("order")->get()
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['name'];
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Download);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ventor\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function show(Download $download)
    {
        return $download;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ventor\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function edit(Download $download)
    {
        return $download;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ventor\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Download $download)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $download, new Download);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ventor\Download  $download
     * @return \Illuminate\Http\Response
     */
    public function destroy(Download $download)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($download, new Download);
    }

    /////////////////
    public function order(Request $request)
    {

        return Download::order($request);

    }

    public function orderCategories(Request $request)
    {
        $data = Content::section("categoriesDownload");
        if (!$data) {
            $data = Content::create(
                ['section' => "categoriesDownload", 'data' => []]
            );
            Ticket::add(1, $data->id, 'contents', 'Se creó el orden de las categorías de Descargas', [null, null, null]);
        } else {
            $valueNew = json_encode($request->category);
            if (gettype($data->data) == "array")
                $valueOld = json_encode($data->data);
            $data->fill(['data' => $request->category]);
            $data->save();
            if ($valueOld != $valueNew) {
                Ticket::add(3, $data->id, 'contents', 'Se modificó el valor', [$valueOld, $valueNew, 'data']);
            }
        }

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Orden guardado"
        ], 200);
    }
}
