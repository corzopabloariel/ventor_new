<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Email;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;

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
            $elements = Client::where("nrocta", "LIKE", "%{$request->search}%")->
                orWhere("razon_social", "LIKE", "%{$request->search}%")->
                orWhere("nrodoc", "LIKE", "%{$request->search}%")->
                orWhere("telefn", "LIKE", "%{$request->search}%")->
                orWhere("direml", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);
        } else
            $elements = Client::paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/clients"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Client::count(), 0, ",", "."),
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
                ], [
                    "function" => "access",
                    "b" => "btn-danger",
                    "i" => "fas fa-user",
                    "t" => "acceder como usuario",
                ], [
                    "function" => "history",
                    "b" => "btn-dark",
                    "i" => "fas fa-history",
                    "t" => "historial de cambios",
                ]
            ],
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['nrocta', 'razon_social', 'nrodoc', 'telefn', 'direml'];
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

    public function load($fromCron = false)
    {
        set_time_limit(0);
        $model = new Client();
        $property = $model->getFillable();
        $arr_err = [];
        $file = configs("FILE_CLIENTS", env('FILE_CLIENTS'));
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
                    $data = array_combine($property, $aux);
                    $client = Client::create($data);
                    $user = User::type("USR")->where('username', $client->nrodoc)->first();
                    $data = array_combine(
                        ['uid', 'docket', 'name', 'username', 'phone', 'email', 'role', 'password'],
                        [$client->_id, $client->nrocta, $client->razon_social, $client->nrodoc, $client->telefn, $client->direml, 'USR', $client->nrodoc]
                    );
                    if ($user) {
                        $user->history($data);
                        $data['password'] = $user->password;
                        $user = User::mod($data, $user);
                    } else {
                        $user = User::create($data);
                    }
                    $users_ids[] = $user->id;
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            if (!empty($users_ids)) {
                User::removeAll($users_ids, 0);
                User::type("USR")->where("test", false)->whereNotIn("id", $users_ids)->delete();
            }
            fclose($file);
            if ($fromCron) {
                return "Clientes insertados: " . Client::count() . " / Errores: " . count($arr_err);
            }
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Documentos insertados: " . Client::count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        if ($fromCron) {
            return "Archivo de Clientes no encontrado";
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
        $user = User::where('uid', $clientID)->first();
        $client = Client::find($clientID);
        $user->fill(["password" => \Hash::make($request->password)]);
        $user->save();

        Ticket::create([
            'type' => 3,
            'table' => 'users',
            'table_id' => $user->id,
            'obs' => '<p>Cambio de contraseña</p>',
            'user_id' => \Auth::user()->id
        ]);
        // Enviar mail
        if ($request->has("notice")) {
            $html = "";
            $html .= "<p>Datos de su cuenta</p>";
            $html .= "<p><strong>Usuario:</strong> {$user->username}</p>";
            $html .= "<p><strong>Contraseña:</strong> {$request->password}</p>";
            $subject = 'Se restableció su contraseña';
            $to = $user->email;
            $email = Email::create([
                'use' => 0,
                'subject' => $subject,
                'body' => $html,
                'from' => env('MAIL_BASE'),
                'to' => $to
            ]);
            Ticket::create([
                'type' => 4,
                'table' => 'users',
                'table_id' => $user->id,
                'obs' => '<p>Envio de mail con blanqueo de contraseña</p><p><strong>Tabla:</strong> emails / <strong>ID:</strong> ' . $email->id . '</p>',
                'user_id' => \Auth::user()->id
            ]);
            Mail::to($to)
                ->send(
                    new BaseMail(
                        $subject,
                        'La contraseña se modificó a pedido de uds.',
                        $html)
                );
        }

        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => "Contraseña blanqueada del cliente: " . $client->razon_social
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function access(Request $request, Client $client)
    {
        try {
            if (session()->has('accessADM') && session()->get('accessADM')->uid == $client->_id) {
                if ($request->session()->has('markup')) {
                    $request->session()->forget('markup');
                }
                if ($request->session()->has('accessADM')) {
                    $request->session()->forget('accessADM');
                }
                if ($request->session()->has('type')) {
                    $request->session()->forget('type');
                }
                return \Redirect::route('index', ['link' => 'pedido']);
            }
            $user = $client->user();
            $cart = Cart::last($user);
            if ($cart)
                session(['cart' => $cart->data]);
            session(['accessADM' => $user]);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => 1,
                "txt" => "No se encontró el cliente"
            ], 200);
        }
        return response()->json([
            "error" => 0,
            "success" => true
        ], 200);
    }
}
