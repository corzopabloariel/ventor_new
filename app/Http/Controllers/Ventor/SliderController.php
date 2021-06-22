<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Ventor\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($section)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['slider']) || isset($permissions['slider']) && !$permissions['slider']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Sliders']);
        }
        $elements = Slider::section($section)->orderBy('order')->paginate(PAGINATE);
        $data = [
            "view" => "element",
            "elements" => $elements,
            "entity" => "slider",
            "section" => "Slider: " . strtoupper($section),
            "values_form" => [
                [
                    "id" => "slider_section",
                    "value" => $section
                ]
            ],
            "buttons" => [
                [
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
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, null, new Slider);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ventor\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        return $slider;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ventor\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return $slider;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ventor\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->store($request, $slider, new Slider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ventor\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        return (new \App\Http\Controllers\Auth\BasicController)->delete($slider, new Slider);
    }
}
