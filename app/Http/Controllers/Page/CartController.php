<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use App\Models\Ventor\Site;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transport;
use App\Models\Client;
use App\Models\Email;
use App\Http\Controllers\Page\BasicController;
use Excel;
use Mpdf\Mpdf;
use PDF;
use App\Exports\OrderExport;
use App\Models\Ventor\Api;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;
use App\Mail\OrderMail;
use Jenssegers\Agent\Agent;

class CartController extends Controller
{
    public $products;

    private $agent;
    public function __construct()
    {
        $this->agent = new Agent();
        $this->products = [];
        $this->middleware('auth');
    }

    public function client(Request $request)
    {
        $nrocta = $request->nrocta;
        if ($request->has("client"))
            session(['nrocta' => $nrocta]);
        else
            session(['nrocta_client' => $nrocta]);
        return 1;
    }

    public function confirm(Request $request)
    {
        if (!$request->session()->has('order')) {
            return \Redirect::route('index');
        }
        if (!session()->has('accessADM')) {
            if (\Auth::user()->role == "ADM" || \Auth::user()->role == "EMP" || \Auth::user()->role == "VND") {
                if (!$request->session()->has('nrocta_client'))
                    return back()->withErrors(['password' => "Seleccione un cliente"]);
            }
        }
        $order = $request->session()->get('order');
        $request->session()->forget('order');
        session(['order_confirm' => $order]);
        if ($request->session()->has('nrocta_client')) {
            $request->session()->forget('nrocta_client');
        }
        $site = new Site("confirm");
        $site->setRequest($request);
        $data = $site->elements();
        $data["order"] = $order;
        
        //if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        //return view('page.mobile', compact('data'));
    }

    public function pdf(Request $request)
    {
        if ($request->has('order_id__pedidos')) {
            $order = Order::where("_id", $request->order_id__pedidos)->first();
        } else {
            if (!$request->session()->has('order_confirm')) {
                return \Redirect::route('index');
            }
            $order = $request->session()->get('order_confirm');
            $request->session()->forget('order_confirm');
            if ($request->session()->has('nrocta_client'))
                $request->session()->forget('nrocta_client');
        }
        $data = ["order" => $order];
        return view('page.pdf_order', $data);
    }

    public function xls(Request $request)
    {
        return Excel::download(
            new OrderExport($request->order_id__pedidos),
                'PEDIDO.xls'
            );
    }

    public function send(Request $request)
    {
        return Cart::forward($request);
    }

    public function checkout(Request $request)
    {
        if ($request->has('empty')) {
            return Cart::empty($request);
        }
        ////////////////////////////////
        if ($request->session()->has('order')) {
            return \Redirect::route('order.success');
        }
        if ($request->method() == 'GET') {
            if (!session()->has('accessADM')) {
                if (\Auth::user()->role == 'ADM' || \Auth::user()->role == 'EMP' || \Auth::user()->role == 'VND') {
                    if (!$request->session()->has('nrocta_client'))
                        return back()->withErrors(['password' => 'Seleccione un cliente']);
                }
            }
            if (!$request->session()->has('cart'))
                return \Redirect::route('order');
            $data = Cart::checkout($request);
            //if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            //return view('page.mobile', compact('data'));
        }
        // POST del pedido
        return Cart::confirm($request, session()->has('accessADM') ? session()->get('accessADM') : null);
    }

    public function show(Request $request)
    {
        return Cart::show($request);
    }

    public function add(Request $request)
    {
        return Cart::add($request);
    }
}
