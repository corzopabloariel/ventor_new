<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ventor\Ticket;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $elements = User::where("role", "=", "EMP")->where(function($query) use ($request) {
                $query->where("docket", "LIKE", "%{$request->search}%")
                        ->orWhere("name", "LIKE", "%{$request->search}%")
                        ->orWhere("username", "LIKE", "%{$request->search}%")
                        ->orWhere("email", "LIKE", "%{$request->search}%");
            })->paginate(PAGINATE);
        } else
            $elements = User::type("EMP")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/employees"),
            "elements" => $elements,
            "entity" => "employee",
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(User::type("EMP")->count(), 0, ",", "."),
            "placeholder" => "todos los campos",
            "section" => "Empleados",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ], [
                    "function" => "access",
                    "b" => "btn-dark",
                    "i" => "fas fa-universal-access",
                    "t" => "accesos y acciones permitidas",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ], [
                    "function" => "cart",
                    "b" => "btn-primary",
                    "i" => "fas fa-cart-plus",
                    "t" => "cantidad de carritos",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['docket', 'name', 'username', 'email'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }

    public function users(Request $request)
    {
        if (isset($request->search)) {
            $elements = User::where("role", "=", "ADM")->where(function($query) use ($request) {
                $query->where("docket", "LIKE", "%{$request->search}%")
                        ->orWhere("name", "LIKE", "%{$request->search}%")
                        ->orWhere("username", "LIKE", "%{$request->search}%")
                        ->orWhere("email", "LIKE", "%{$request->search}%");
            })->paginate(PAGINATE);

        } else
            $elements = User::type("ADM")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/users"),
            "elements" => $elements,
            "entity" => "employee",
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(User::type("ADM")->count(), 0, ",", "."),
            "placeholder" => "todos los campos",
            "section" => "Empleados ADM",
            "buttons" => [
                [
                    "f" => "listar",
                    "b" => "btn-primary",
                    "i" => "fab fa-audible",
                    "t" => "actualizar ADM",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ],
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['docket', 'name', 'username', 'email'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }

    public function list() {
        $users = User::whereIn("role", ["ADM", "EMP"])->where("id", "!=", \Auth::user()->id)->get();
        $data = collect($users)->map(function($x){
            $h = "";
            $h .= "<tr>";
                $h .= "<td>{$x->username}</td>";
                $h .= "<td>{$x->name}</td>";
                $h .= "<td>";
                    $h .= "<select name='role[]' class='form-control role-user'>";
                        $h .= "<option " . ($x->role == "ADM" ? "selected" : "") . " value='ADM'>Administrador</option>";
                        $h .= "<option " . ($x->role == "EMP" ? "selected" : "") . " value='EMP'>Empleado</option>";
                    $h .= "</select>";
                    $h .= "<input name='id[]' type='hidden' value='{$x->id}'/>";
                $h .= "</td>";
            $h .= "</tr>";
            return $h; });
        return response()->json([
            $data
        ], 200);
    }

    public function role(Request $request) {
        try {
            for ($i = 0; $i < count($request->id); $i++) {
                $user = User::find($request->id[$i]);
                if ($user->role == $request->role[$i])
                    continue;
                User::history(['role' => $request->role[$i]], $user->id);
                $user->fill(['role' => $request->role[$i]]);
                $user->save();
            }
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Datos modificados"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => 1,
                "txt" => "Ocurrió un error"
            ], 200);
        }
    }

    /**
     *
     * @param  String $row
     * @return String
     */
    public function clearRow($row)
    {
        $value = utf8_encode(trim($row));
        return $value === "" ? NULL : $value;
    }

    public function load($fromCron = false)
    {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['employees']) || isset($permissions['employees']) && !$permissions['employees']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return User::updateCollection($fromCron);

    }

    public function access(Request $request) {
        $user = User::find($request->id);
        return responseReturn(false, '', 0, 200, ['user' => $user]);
    }

    public function permissions(Request $request) {
        $user = User::find($request->id);
        $result = $user->updatePermissions($request);

        return $result;
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
