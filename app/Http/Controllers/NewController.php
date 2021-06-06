<?php

namespace App\Http\Controllers;

use App\Models\Ventor\Newness;
use App\Models\Ventor\Ticket;
use Illuminate\Http\Request;
class NewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $elements = Newness::where("name", "LIKE", "%{$request->search}%")->
                orderBy("order")->paginate(PAGINATE);
        } else
            $elements = Newness::orderBy("order")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/news"),
            "elements" => $elements,
            "entity" => "new",
            "placeholder" => "nombre",
            "section" => "Novedades",
            "buttons" => [
                [
                    "f" => "order",
                    "b" => "btn-primary",
                    "i" => "fas fa-sort",
                    "t" => "ordernar Novedades",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ],
            "all" => Newness::orderBy("order")->get()
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Newness);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ventor\Newness  $newness
     * @return \Illuminate\Http\Response
     */
    public function show(Newness $newness)
    {
        return $newness;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ventor\Newness  $newness
     * @return \Illuminate\Http\Response
     */
    public function edit(Newness $newness)
    {
        return $newness;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ventor\Newness  $newness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Newness $newness)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $newness, new Newness);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ventor\Newness  $newness
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newness $newness)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($newness, new Newness);
    }

    //////////////
    public function order(Request $request)
    {

        return Newness::order($request);

    }
}
