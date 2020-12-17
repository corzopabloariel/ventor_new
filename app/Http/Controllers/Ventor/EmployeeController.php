<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            "placeholder" => "todos los campos",
            "section" => "Empleados",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ]
            ],
            "buttonsScript" => view('adm.scripts.employee')->render()
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['docket', 'name', 'username', 'email'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
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
        $filename = implode('/', [public_path(), env('FOLDER_TXT'), env('FILE_EMPLOYEES')]);
        if (file_exists($filename))
        {
            User::type("EMP")->delete();
            $file = fopen($filename, 'r');
            while (!feof($file))
            {
                $row = trim(fgets($file));
                if (empty($row) || strpos($row, 'Cuenta') !== false)
                {
                    continue;
                }
                $aux = explode(env('SEPARATOR'), $row);
                $aux = array_map('self::clearRow', $aux);
                if (empty($aux))
                    continue;
                try {
                    $data = array_combine(['docket', 'name', 'username', 'email'], $aux);
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['phone'] = $data['email'];
                        unset($data['email']);
                    }
                    $data['password'] = 'pablopablo';
                    $data['username'] = "EMP_{$data['username']}";
                    $data['role'] = 'EMP';
                    if ($data['username'] == 'EMP_28465591' || $data['username'] == 'EMP_12557187' || $data['username'] == 'EMP_12661482')
                        $data['role'] = 'ADM';
                    $user = User::create($data);
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            fclose($file);
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
