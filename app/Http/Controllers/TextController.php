<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\Http\Request;

class TextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['texts']) || isset($permissions['texts']) && !$permissions['texts']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Textos']);
        }
        if (isset($request->search)) {
            $elements = Text::where("name", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);
        } else
            $elements = Text::orderBy("name")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to("adm/texts"),
            "elements" => $elements,
            "entity" => "text",
            "placeholder" => "nombre",
            "section" => "Textos",
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Text);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Text  $text
     * @return \Illuminate\Http\Response
     */
    public function show(Text $text)
    {
        return $text;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Text  $text
     * @return \Illuminate\Http\Response
     */
    public function edit(Text $text)
    {
        return $text;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Text  $text
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Text $text)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $text, new Text);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Text  $text
     * @return \Illuminate\Http\Response
     */
    public function destroy(Text $text)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($text, new Text);
    }
}
