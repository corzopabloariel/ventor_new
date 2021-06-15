<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Ventor;
use App\Models\Ventor\Ticket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Content;
use App\Models\Order;
use App\Models\User;
use MongoDB\BSON\Regex;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:adm']);
    }

    public function index(Request $request)
    {
        if ($request->method() == "GET") {
            $prueba = User::where("test", true)->first();
            $data = ["view" => "home", "prueba" => $prueba];
            return view('home',compact('data'));
        }
        $user = User::type("USR")->where('username', $request->username)->first();
        $attr = [
            'name' => 'required',
            'username' => 'required|max:20|unique:users,username'
        ];
        if ($user)
            $attr = [
                'name' => 'required',
                'username' => 'required|max:20|unique:users,username,'.$user->id,
            ];
        $validator = Validator::make($request->all(), $attr);
        if($validator->fails()){
            return back()->withErrors(['password' => "Faltan datos o son incorrectos"])->withInput();
        }
        $data = $request->except(['_token']);
        $data["role"] = "USR";
        $data["test"] = true;
        if ($user) {
            if (empty($data["password"]))
                $data['password'] = $user->password;
            else
                $data['password'] = \Hash::make($data["password"]);
            $user->history($data);
            $user = User::mod($data, $user);
        } else {
            $user = User::create($data);
        }
        return back();
    }

    public function data(Request $request)
    {
        $data = Ventor::first();
        if (empty($request->all())) {
            if(empty($data)) {
                $data = Ventor::create([
                    'address' => [],
                    'phone' => [],
                    'email' => [],
                    'social' => [],
                    'metadata' => [],
                    'images' => [],
                    'section' => [],
                    'miscellaneous' => [],
                    'form' => []
                ]);
            }
            $data = [
                "view" => "ventor",
                "elements" => $data,
                "section" => "Datos básicos",
                "buttons" => [
                    [
                        "f" => "history",
                        "b" => "btn-dark",
                        "i" => "fas fa-history",
                        "t" => "historial de cambios",
                    ]
                ]
            ];
            return view('home',compact('data'));
        }
        
        $aux = (new \App\Http\Controllers\Auth\BasicController)->store($request, $data, new Ventor, null, true);
        $OBJ = json_decode($aux, true);
        if ($OBJ["error"] == 0) {
            if ($OBJ["success"]) {
                foreach ($OBJ["data"] AS $k => $v) {
                    $valueNew = $v;
                    $valueOld = $data[$k];
                    if (gettype($valueNew) == "array")
                        $valueNew = json_encode($valueNew);
                    if (gettype($valueOld) == "array")
                        $valueOld = json_encode($valueOld);
                    if ($valueOld != $valueNew) {
                        Ticket::create([
                            'type' => 3,
                            'table' => 'ventor',
                            'table_id' => $data->id,
                            'obs' => '<p>Se modificó el valor de "' . $k . '" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                            'user_id' => \Auth::user()->id
                        ]);
                    }
                }
                $data->fill($OBJ["data"]);
                $data->save();
            }
        }
        return $aux;
    }

    public function content(Request $request, $section)
    {
        $data = Content::section($section);
        if (empty($request->all())) {
            if (!$data) {
                $data = Content::create(
                    ['section' => $section, 'data' => []]
                );
            }
            $data = [
                "content" => $section,
                "view" => "content",
                "elements" => $data,
                "section" => "Contenido de " . strtoupper($section),
                "buttons" => [
                    [
                        "f" => "history",
                        "b" => "btn-dark",
                        "i" => "fas fa-history",
                        "t" => "historial de cambios",
                    ]
                ]
            ];
            return view('home',compact('data'));
        }
        $aux = (new \App\Http\Controllers\Auth\BasicController)->store($request, $data->data, new Content, null, true);
        $OBJ = json_decode($aux, true);
        if ($OBJ["error"] == 0) {
            if ($OBJ["success"]) {
                foreach ($OBJ["data"] AS $k => $v) {
                    $valueNew = $v;
                    $valueOld = isset($data->data[$k]) ? $data->data[$k] : "";
                    if (gettype($valueNew) == "array")
                        $valueNew = json_encode($valueNew);
                    if (gettype($valueOld) == "array")
                        $valueOld = json_encode($valueOld);
                    if ($valueOld != $valueNew) {
                        Ticket::create([
                            'type' => 3,
                            'table' => 'contents',
                            'table_id' => $data->id,
                            'obs' => '<p>Se modificó el valor de "' . $k . '" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                            'user_id' => \Auth::user()->id
                        ]);
                    }
                }
                $data->fill(['data' => $OBJ["data"]]);
                $data->save();
            }
        }
        return $aux;
    }

    public function history(Request $request)
    {
        $id = $request->id;
        $table = $request->table;
        switch ($table) {
            case "clients":
                $table = "users";
                $aux = \DB::table($table)->where('uid', $id)->first();
                $id = $aux->id;
                break;
        }
        $tickets = Ticket::show($id, $table);
        
        return response()->json([
            "error" => 0,
            "success" => true,
            "txt" => $tickets
        ], 200);
    }

    public function orders(Request $request)
    {
        if (isset($request->search)) {
            $elements = Order::where("transport.code", $request->search)->
                orWhere("client.nrocta", $request->search)->
                orWhere("seller.code", $request->search)->
                orWhere("uid", "LIKE", "%{$request->search}%")->
                orWhere("products", 'regexp', '/.*'.$request->search.'/i')->
                orderBy("_id", "DESC")->
                paginate(PAGINATE);
        } else
            $elements = Order::orderBy("_id", "DESC")->paginate(PAGINATE);
        $data = [
            "view" => "orders",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/orders"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Order::count(), 0, ",", "."),
            "placeholder" => "nro. pedido, nro. cliente, cód. transporte y cód. vendedor",
            "section" => "Pedidos",
            "buttons" => [
                [
                    "function" => "history",
                    "b" => "btn-danger",
                    "i" => "fas fa-file-pdf",
                    "t" => "descargar pedido",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['client', 'transport', 'seller'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }
}
