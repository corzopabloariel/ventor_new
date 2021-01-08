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
        foreach($request->ids AS $type => $ids) {
            for($i = 0; $i < count($ids); $i++) {
                $download = Download::find($ids[$i]);
                $valueNew = $i;
                $valueOld = $download->order;
                $download->fill(["order" => $i]);
                $download->save();
                if ($valueOld != $valueNew) {
                    Ticket::create([
                        'type' => 3,
                        'table' => 'downloads',
                        'table_id' => $download->id,
                        'obs' => '<p>Se modificó el valor de "order" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                        'user_id' => \Auth::user()->id
                    ]);
                }
            }
        }

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Orden guardado"
        ], 200);
    }

    public function orderCategories(Request $request)
    {
        $data = Content::section("categoriesDownload");
        if (!$data) {
            $data = Content::create(
                ['section' => "categoriesDownload", 'data' => []]
            );
            Ticket::create([
                'type' => 1,
                'table' => 'contents',
                'table_id' => $data->id,
                'obs' => '<p>Se creó el orden de las categorías de Descargas</p>',
                'user_id' => \Auth::user()->id
            ]);
        } else {
            $valueNew = json_encode($request->category);
            if (gettype($data->data) == "array")
                $valueOld = json_encode($data->data);
            $data->fill(['data' => $request->category]);
            $data->save();
            if ($valueOld != $valueNew) {
                Ticket::create([
                    'type' => 3,
                    'table' => 'contents',
                    'table_id' => $data->id,
                    'obs' => '<p>Se modificó el valor de "data" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
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
