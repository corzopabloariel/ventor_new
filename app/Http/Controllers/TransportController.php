<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['transports']) || isset($permissions['transports']) && !$permissions['transports']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Transportes']);
        }
        if (isset($request->search)) {
            $elements = Transport::where("code", "LIKE", "%{$request->search}%")->
                orWhere("description", "LIKE", "%{$request->search}%")->
                orWhere("address", "LIKE", "%{$request->search}%")->
                orWhere("phone", "LIKE", "%{$request->search}%")->
                orWhere("person", "LIKE", "%{$request->search}%")->
                orderBy("code")->paginate(PAGINATE);

        } else
            $elements = Transport::orderBy("code")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/transports"),
            "elements" => $elements,
            "entity" => "transport",
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Transport::count(), 0, ",", "."),
            "placeholder" => "todos los campos",
            "section" => "Transportes",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ["code", "description", "address", "phone", "person"];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }


    public function load(Bool $fromCron = false) {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['transports']) || isset($permissions['transports']) && !$permissions['transports']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return Transport::updateCollection($fromCron);

    }
}
