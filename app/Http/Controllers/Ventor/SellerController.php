<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['sellers']) || isset($permissions['sellers']) && !$permissions['sellers']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Vendedores']);
        }
        if (isset($request->search)) {
            $elements = User::type("VND")->where("docket", "LIKE", "%{$request->search}%")->
                orWhere("dockets", "LIKE", "%{$request->search}%")->
                orWhere("name", "LIKE", "%{$request->search}%")->
                orWhere("username", "LIKE", "%{$request->search}%")->
                orWhere("phone", "LIKE", "%{$request->search}%")->
                orWhere("email", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);

        } else
            $elements = User::type("VND")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/sellers"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(User::type("VND")->count(), 0, ",", "."),
            "entity" => "seller",
            "placeholder" => "todos los campos",
            "section" => "Vendedores",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['docket', 'name', 'username', 'phone', 'email'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }

    /**
     *
     * @param  String $row
     * @return String
     */
    public function clearRow($rowTransport)
    {
        $value = utf8_encode(trim($rowTransport));
        return $value === "" ? NULL : $value;
    }

    public function load($fromCron = false)
    {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['sellers']) || isset($permissions['sellers']) && !$permissions['sellers']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return User::updateSellerCollection($fromCron);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }
}
