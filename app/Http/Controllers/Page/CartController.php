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
use App\Models\User;
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
        // Limpio carrito
        Cart::empty($request);
        $site = new Site("confirm");
        $site->setRequest($request);
        $data = $site->elements();
        $no_img = asset("images/no-img.png");
        $data['tbody'] = collect($order->products)->map(function($item, $key) use ($no_img) {
            $product = $item['product'];
            $price = $product["priceNumber"] * $item["quantity"];
            $price = number_format($price, 2, ",", ".");
            $img = $product["images"][0];
            $html = "<tr>";
                $html .= "<td><img src='{$img}' alt='{$product["name"]}' onerror=\"this.src='{$no_img}'\"/></td>";
                $html .= "<td>";
                    if (isset($product["code"]))
                        $html .= "<p class=\"mb-0 product--code\">{$product["code"]}</p>";
                    if (isset($product["brand"]))
                        $html .= "<p class=\"mb-0 product--for\">{$product["brand"]}</p>";
                    $html .= "<p>{$product["name"]}</p>";
                $html .= "</td>";
                $html .= "<td class='text-right --one-line'>" . $product["price"] . "</td>";
                $html .= "<td class='text-center'>" . $item["quantity"] . "</td>";
                $html .= "<td class='text-right --one-line'>$ " . $price . "</td>";
            $html .= "</tr>";
            return $html;
        })->join("");
        $data["order"] = $order;
        $data['message'] = 'El pedido fue enviado con éxito.';
        if (empty(\Auth::user()->uid) &&
            isset($data["order"]["client_id"]) &&
            isset($data["order"]["user_id"]) &&
            isset($data["order"]["client"]) &&
            isset($data["order"]["client"]["razon_social"]) &&
            $data["order"]["user_id"] != $data["order"]["client_id"]
        ) {
            $data['message'] = 'El pedido del cliente <strong>'.$data["order"]["client"]["razon_social"].'</strong> fue enviado con éxito.';
        }
        
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
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
            if ($request->has('username')) {
                $user = User::where('username', $request->username)->first();
                $user->addNotice(['message' => 'Espere, se recargará la página', 'action' => 'clearCart']);
                return responseReturn(false, 'Carrito vaciado');
            }
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
                        return redirect()->route('order')->withErrors(['password' => 'Seleccione un cliente']);
                }
            }
            $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
            $products = readJsonFile(session()->has('accessADM') ?
                "/file/cart_".session()->get('accessADM')->id."-1.json" :
                "/file/cart_".\Auth::user()->id."-{$number}.json"
            );
            if (empty($products))
                return \Redirect::route('order');
            $data = Cart::checkout($request, \Auth::user()->isShowQuantity());
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
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
