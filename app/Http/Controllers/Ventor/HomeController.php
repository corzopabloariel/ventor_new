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
use App\Models\Email;
use App\Models\User;
use App\Models\Ventor\PaginatorApi;

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
            User::history($data, $user->id);
            $user = User::mod($data, $user);
        } else {
            $user = User::create($data);
        }
        return back();
    }

    public function data(Request $request)
    {
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['data']) || isset($permissions['data']) && !$permissions['data']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso a Datos']);
        }
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
                        Ticket::add(3, $data->id, 'ventor', 'Se modificó el valor', [$valueOld, $valueNew, $k]);
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
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['contents']) || isset($permissions['contents']) && !$permissions['contents']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al Contenido']);
        }
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
                        Ticket::add(3, $data->id, 'contents', 'Se modificó el valor', [$valueOld, $valueNew, $k]);
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
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['orders']) || isset($permissions['orders']) && !$permissions['orders']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Pedidos']);
        }
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

    public function emails(Request $request)
    {
        if ($request->method() == 'GET' || isset($request->search)) {

            $emails = Email::where('from', '!=', '');
            $paginate = $request->has('paginate') ? (int) $request->get('paginate') : 10;
            $page = $request->has('page') ? (int) $request->get('page') : 1;
            $slug = 'adm/emails';
            if ($request->has('search')) {

                $emails = $emails->where('subject', 'LIKE', '%'.$request->get('search').'%');
                $slug .= '?search='.$request->get('search');

            }
            $total = $emails->count();
            $totalPages = ceil($total / $paginate);
            $emails = $emails->
                orderBy("id", "DESC")->
                paginate(PAGINATE);
            $data = array();
            $data['page'] = $page;
            $data['elements'] = $emails;
            $data['total'] = array(
                'emails' => $total,
                'pages'  => $totalPages
            );
            $paginator = new PaginatorApi($data['total']['emails'], $data['total']['pages'], $data['page'], $slug);
            $data['paginator'] = $paginator->gets();
            $table = view(
                'admin.emails.table',
                $data
            )->render();
            $blade = view(
                'admin.emails',
                array(
                    'table' => $table,
                    'total' => $data['total']['emails'],
                    'form'  => array(
                        'url'           => \URL::to(\Auth::user()->redirect().'/emails'),
                        'placeholder'   => 'Buscar en título',
                        'search'        => $request->has('search') ? $request->get('search') : null
                    )
                )
            );
            return view('admin',
                array(
                    'blade'     => $blade,
                    'script'    => 'emails',
                    'modal'     => 'emails'
                )
            );

        }
        $email = Email::find($request->id);
        if ($email) {
            return responseReturn(false, '', 0, 200, ['email' => $email]);
        }
        return responseReturn(false, 'Datos no encontrados', 1);
    }

    public function export(Request $request, $type) {
        $extValidate = [
            'dbf',
            'xls',
            'txt',
            'csv'
        ];
        if (in_array($type, $extValidate)) {
            \Artisan::call('file:'.$type);
            return responseReturn(false, '');
        } else {
            return responseReturn(false, 'Extensión no válida', 1, 400);
        }
    }
}
