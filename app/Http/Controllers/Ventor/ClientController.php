<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $elements = User::type("USR")->where("docket", "LIKE", "%{$request->search}%")->
                orWhere("name", "LIKE", "%{$request->search}%")->
                orWhere("username", "LIKE", "%{$request->search}%")->
                orWhere("phone", "LIKE", "%{$request->search}%")->
                orWhere("email", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);

        } else
            $elements = Client::paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/clients"),
            "elements" => $elements,
            "entity" => "client",
            "placeholder" => "todos los campos",
            "section" => "Clientes",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ], [
                    "function" => "password",
                    "b" => "btn-dark",
                    "i" => "fas fa-key",
                    "t" => "blanquear contraseña",
                ], [
                    "function" => "data",
                    "b" => "btn-info",
                    "i" => "far fa-eye",
                    "t" => "ver datos",
                ]
            ],
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
        $model = new Client();
        $property = $model->getFillable();
        $arr_err = [];
        $filename = implode('/', [public_path(), env('FOLDER_TXT'), env('FILE_CLIENTS')]);
        if (file_exists($filename))
        {
            User::type("USR")->delete();
            Client::removeAll();
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
                    $data = array_combine($property, $aux);
                    $client = Client::create($data);
                    /*$user = User::type("USR")->where('username', $client->nrodoc)->first();
                    $data = array_combine(
                        ['uid', 'docket', 'name', 'username', 'phone', 'email', 'role', 'password'],
                        [$client->_id, $client->nrocta, $client->razon_social, $client->nrodoc, $client->telefn, $client->direml, 'USR', $client->nrodoc]
                    );
                    if ($user) {
                        $data['password'] = $user->password;
                        $user = User::mod($data, $user);
                    } else {
                        $user = User::create($data);
                    }*/
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            fclose($file);
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Documentos insertados: " . Client::count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        return response()->json([
            "error" => 1,
            "txt" => "Archivo no encontrado"
        ], 410);
    }

    public function pass(Request $request, $clientID) {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                "error" => 1,
                "txt" => "Contraseña necesaria."
            ], 200);
        }
        $user = User::type("USR")->where('uid', $clientID)->first();
        $client = Client::find($clientID);
        $user->fill(["password" => \Hash::make($request->password)]);
        $user->save();
        // Enviar mail
        if ($request->has("notice")) {}

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Contraseña blanqueada del cliente: " . $client->razon_social
        ], 200);
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
