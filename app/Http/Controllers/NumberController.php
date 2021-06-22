<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\Ventor\Ticket;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['numbers']) || isset($permissions['numbers']) && !$permissions['numbers']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Números']);
        }
        $elements = Number::orderBy("order")->paginate(PAGINATE);
        $data = [
            "view" => "element",
            "elements" => $elements,
            "entity" => "number",
            "all" => Number::orderBy("order")->get(),
            "section" => "Números",
            "buttons" => [
                [
                    "f" => "order",
                    "b" => "btn-primary",
                    "i" => "fas fa-sort",
                    "t" => "ordernar",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ]
        ];
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Number);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function show(Number $number)
    {
        return $number;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function edit(Number $number)
    {
        return $number;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Number $number)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $number, new Number);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function destroy(Number $number)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($number, new Number);
    }

    /////////////////
    public function order(Request $request)
    {

        return Number::order($request);

    }
}
