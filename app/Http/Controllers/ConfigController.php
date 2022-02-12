<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['configs']) || isset($permissions['configs']) && !$permissions['configs']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Configuraciones']);
        }
        if (isset($request->search)) {
            $elements = Config::where('visible', true)->where("name", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);
        } else
            $elements = Config::where('visible', true)->orderBy("name")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to("adm/configs"),
            "elements" => $elements,
            "entity" => "config",
            "placeholder" => "nombre",
            "section" => "Configuración",
            "buttons" => [
                [
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Config);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        return $config;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function edit(Config $config)
    {
        return $config;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $config, new Config);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy(Config $config)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($config, new Config);
    }
}
