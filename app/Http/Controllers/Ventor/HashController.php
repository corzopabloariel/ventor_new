<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Hashfile;
use Illuminate\Http\Request;

class HashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Hashs']);
        }
        if (isset($request->search)) {
            $elements = Hashfile::where("hash", "LIKE", "%{$request->search}%")->paginate(PAGINATE);
        } else
            $elements = Hashfile::paginate(PAGINATE);
        $buttons = [];
        if (!empty($permissions) && isset($permissions['clients']) && !$permissions['clients']['update']) {
            array_shift($buttons);
        }
        $data = [
            "view" => "element",
            "url_search" => \URL::to("adm/hashfiles"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Hashfile::count(), 0, ",", "."),
            "entity" => "hash",
            "placeholder" => "hash",
            "section" => "Hashs archivos",
            "help" => "Comparta la url con la extensión correspondiente (dbf, txt o xls)",
            "buttons" => $buttons,
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['hash'];
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Hashfile);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hashfile  $hashfile
     * @return \Illuminate\Http\Response
     */
    public function show(Hashfile $hashfile)
    {
        return $hashfile;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hashfile  $hashfile
     * @return \Illuminate\Http\Response
     */
    public function edit(Hashfile $hashfile)
    {
        return $hashfile;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hashfile  $hashfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hashfile $hashfile)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $hashfile, new Hashfile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hashfile  $hashfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hashfile $hashfile)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($hashfile, new Hashfile);
    }
}
