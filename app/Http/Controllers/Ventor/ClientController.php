<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Email;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use App\Models\Product;
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
        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['read'])) {
            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Clientes']);
        }
        if (isset($request->search)) {
            $elements = Client::where("nrocta", "LIKE", "%{$request->search}%")->
                orWhere("razon_social", "LIKE", "%{$request->search}%")->
                orWhere("nrodoc", "LIKE", "%{$request->search}%")->
                orWhere("telefn", "LIKE", "%{$request->search}%")->
                orWhere("direml", "LIKE", "%{$request->search}%")->
                paginate(PAGINATE);
        } else
            $elements = Client::paginate(PAGINATE);
        $buttons = [
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
                "function" => "cart",
                "b" => "btn-warning",
                "i" => "fas fa-shopping-cart",
                "t" => "ver carrito",
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
        ];
        if (!empty($permissions) && isset($permissions['clients']) && !$permissions['clients']['update']) {
            array_shift($buttons);
        }
        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/clients"),
            "elements" => $elements,
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Client::count(), 0, ",", "."),
            "entity" => "client",
            "placeholder" => "todos los campos",
            "section" => "Clientes",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => $buttons,
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ['nrocta', 'razon_social', 'nrodoc', 'telefn', 'direml'];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }


    public function load(Bool $fromCron = false) {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return Client::updateCollection($fromCron);

    }

    public function pass(Request $request, Client $client) {

        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['update'])) {
            return responseReturn(false, 'Acción no permitida', 1, 200);
        }
        return $client->changePassword($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function cart(Request $request, Client $client)
    {
        $aux = '<tr><td colspan="6" class="text-center">SIN INFORMACIÓN</td></tr>';
        $lastTicket = null;
        try {
            $lastTicket = $client->user()->tickets()->where('table', 'cart')->orderBy('id', 'desc')->first();
            $lastCart = Cart::last($client->user(), true);
            if ($lastCart) {
                $aux = collect($lastCart->data)->map(function($data, $key) use ($request) {
                    $product = Product::one($request, $key);
                    if (empty($product)) {
                        $product = Product::one($request, $data["product"]["search"], "search");
                        if (empty($product)) {
                            $data["updated"] = 0;
                            return $data;
                        }
                    }
                    $data['product'] = $product;
                    $data["price"] = $product["priceNumber"];
                    $data["updated"] = 1;
                    return $data;
                })->filter(function($value) {
                    return !empty($value);
                })->map(function($data) {
                    return '<tr>' .
                        '<td style="white-space: nowrap;">' . $data['product']['code'] . '</td>' .
                        '<td>' . $data['product']['name'] . '</td>' .
                        '<td style="white-space: nowrap; text-align: right;">' . $data['product']['price'] . '</td>' .
                        '<td class="text-center">' . $data['quantity'] . '</td>' .
                        '<td>' . $data['product']['brand'] . '</td>' .
                        '<td>' . $data['product']['modelo_anio'] . '</td>' .
                        '<td class="text-center">' . ($data['updated'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger" title="Producto no encontrado"></i>') . '</td>' .
                        '</tr>';
                })->join('');
            }
        } catch (\Throwable $th) {
            return response()->json([
                "error" => 1,
                "txt" => "No se encontró el carrito"
            ], 200);
        }
        return response()->json([
            "error" => 0,
            "success" => true,
            "data" => empty($aux) ? '<tr><td colspan="7" class="text-center">Sin información</td></tr>' : $aux,
            "showBtn" => !empty($aux) && $lastCart,
            "cart" => $lastCart,
            "client" => $client,
            "ticket" => $lastTicket
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
        if (!\Auth::user()->isAdmin()) {
            return responseReturn(false, 'Acción no permitida', 1);
        }
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
            return responseReturn(false, 'Cliente no encontrado', 1);
        }
        return responseReturn(false, '');
    }
}
