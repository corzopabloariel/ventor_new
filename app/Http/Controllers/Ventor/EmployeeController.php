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
            $elements = User::type("EMP")->where("docket", "LIKE", "%{$request->search}%")->
                orWhere("name", "LIKE", "%{$request->search}%")->
                orWhere("username", "LIKE", "%{$request->search}%")->
                orWhere("email", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);
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
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
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
            $elements = User::type("ADM")->where("docket", "LIKE", "%{$request->search}%")->
                orWhere("name", "LIKE", "%{$request->search}%")->
                orWhere("username", "LIKE", "%{$request->search}%")->
                orWhere("email", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);

        } else
            $elements = User::type("ADM")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/employees"),
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
                $user->history(['role' => $request->role[$i]]);
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

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function load(Request $request)
    {
        set_time_limit(0);
        $arr_err = [];
        $file = configs("FILE_EMPLOYEES", env('FILE_EMPLOYEES'));
        $filename = implode('/', [public_path(), env('FOLDER_TXT'), $file]);
        if (file_exists($filename))
        {
            $users_ids = [];
            $file = fopen($filename, 'r');
            while (!feof($file))
            {
                $row = trim(fgets($file));
                if (empty($row) || strpos($row, 'Cuenta') !== false)
                {
                    continue;
                }
                $aux = explode(env('SEPARATOR', config("SEPARADOR")), $row);
                $aux = array_map('self::clearRow', $aux);
                if (empty($aux))
                    continue;
                try {
                    $data = array_combine(['docket', 'name', 'username', 'email'], $aux);
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['phone'] = $data['email'];
                        unset($data['email']);
                    }
                    $user = User::where("username", "EMP_{$data['username']}")->first();
                    $data['password'] = env('PASS');
                    $data['username'] = "EMP_{$data['username']}";
                    $data['role'] = 'EMP';
                    if ($data['username'] == 'EMP_28465591' || $data['username'] == 'EMP_12557187' || $data['username'] == 'EMP_12661482')
                        $data['role'] = 'ADM';
                    if ($user) {
                        $user->history($data);
                        $data['password'] = \Hash::make(env('PASS'));
                        $user->fill($data);
                        $user->save();
                    } else
                        $user = User::create($data);
                    $users_ids[] = $user->id;
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            fclose($file);
            //Elimino registros que no esten
            if (!empty($users_ids)) {
                User::removeAll($users_ids, 0, "ADM");
                User::removeAll($users_ids, 0, "EMP");
                User::whereIn("role", ["ADM","EMP"])->where("username", "!=", "pc")->whereNotIn("id", $users_ids)->delete();
            }
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Registros totales: " . User::type("EMP")->count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        return response()->json([
            "error" => 1,
            "txt" => "Archivo no encontrado"
        ], 410);
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
