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
        set_time_limit(0);
        $arr_err = [];
        $file = configs("FILE_SELLERS", config('app.files.sellers'));
        $filename = implode('/', [public_path(), config('app.files.folder'), $file]);
        if (file_exists($filename))
        {
            $users_ids = [];
            $file = fopen($filename, 'r');
            while (!feof($file))
            {
                $row = trim(fgets($file));
                if (empty($row) || strpos($row, 'Apellido,') !== false)
                {
                    continue;
                }
                $aux = explode(configs("SEPARADOR"), $row);
                $aux = array_map('self::clearRow', $aux);
                if (empty($aux))
                    continue;
                try {
                    $data = array_combine(['docket', 'name', 'username', 'phone', 'email'], $aux);
                    if (empty($data['username']))
                        continue;
                    $user = User::where("username", "VND_{$data['username']}")->first();
                    $data['password'] = config('app.pass');
                    $data['username'] = "VND_{$data['username']}";
                    $data['role'] = 'VND';
                    if ($user) {
                        if (empty($user->dockets))
                            $data["dockets"] = [];
                        else
                            $data["dockets"] = $user->dockets;
                        if (!in_array($data["docket"], $data["dockets"]))
                            $data["dockets"][] = $data['docket'];
                        $data["docket"] = $data["dockets"][0];
                        $user->history($data);
                        $data['password'] = \Hash::make(config('app.pass'));
                        $user->fill($data);
                        $user->save();
                    } else
                        $user = User::create($data);
                    $users_ids[] = $user->id;
                } catch (\Throwable $th) {
                    // Enviar error
                    $arr_err[] = $aux;
                }
            }
            if (!empty($users_ids)) {
                User::removeAll($users_ids, 0, "VND");
                User::type("VND")->whereNotIn("id", $users_ids)->delete();
            }
            fclose($file);
            if ($fromCron) {
                return "Vendedores totales: " . User::type("VND")->count() . " / Errores: " . count($arr_err);
            }
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Registros totales: " . User::type("VND")->count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        if ($fromCron) {
            return "Archivo de Vendedores no encontrado";
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
