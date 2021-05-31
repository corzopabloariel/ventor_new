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
        
        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
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
        $orderId = $request->order_id__pedidos;
        $order = Order::where("_id", $orderId)->first();
        $title = $order->title;
        $transport = $order->transport;
        $traCod = str_pad($transport["code"], 2, "0", STR_PAD_LEFT);
        $obs = isset($order->obs) ? $order->obs : "";
        $mensaje = [];
        $mensaje[] = "<&TEXTOS>{$obs}</&TEXTOS>";
        $mensaje[] = "<&TRACOD>{$traCod}|{$transport["description"]} {$transport["address"]}</&TRACOD>";
        
        $to = [env('MAIL_TO')];
        if (env('APP_ENV') == 'production') {
            $to[] = 'sebastianevillarreal@gmail.com';
            if ($codCliente != "PRUEBA")
                $to[] = 'pedidos.ventor@gmx.com';
        }
        $email = Email::create([
            'use' => 0,
            'subject' => $title . " | Reenvío",
            'body' => implode("", $mensaje),
            'from' => env('MAIL_BASE'),
            'to' => $to
        ]);
        try {
            Mail::to($to)
                ->send(
                    new OrderMail(
                        $mensaje,
                        $title,
                        Excel::download(
                            new OrderExport($order->_id),
                                'PEDIDO.xls'
                            )->getFile(), ['as' => 'PEDIDO.xls'])
            );
            $email->fill(["sent" => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(["error" => 1]);
            $email->save();

            return response()->json([
                "error" => 1,
                "mssg" => "Ocurrió un error."
            ], 200);
        }
    }

    public function checkout(Request $request)
    {
        if ($request->has('empty')) {
            $lastCart = Cart::last();
            $valueNew = json_encode([]);
            $valueOld = $lastCart->data;
            $cart = Cart::create(["data" => []]);
            if (gettype($valueNew) == "array")
                $valueNew = json_encode($valueNew);
            if (gettype($valueOld) == "array")
                $valueOld = json_encode($valueOld);
            if ($valueOld != $valueNew) {
                Ticket::create([
                    "type" => 3,
                    "table" => "cart",
                    "table_id" => $cart->id,
                    'obs' => '<p>Se modificó el valor de "data"</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }

            if ($request->session()->has('cart')) {
                $request->session()->forget('cart');
            }
            return json_encode(["error" => 0, "success" => true, "total" => 0, "elements" => 0]);
        }
        ////////////////////////////////
        if ($request->session()->has('order')) {
            return \Redirect::route('order.success');
        }
        if ($request->method() == "GET") {
            if (!session()->has('accessADM')) {
                if (\Auth::user()->role == "ADM" || \Auth::user()->role == "EMP" || \Auth::user()->role == "VND") {
                    if (!$request->session()->has('nrocta_client'))
                        return back()->withErrors(['password' => "Seleccione un cliente"]);
                }
            }
            $site = new Site("checkout");
            $site->setRequest($request);
            $data = $site->elements();
            $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
            if (empty($this->products))
                return \Redirect::route('order');
            $no_img = asset("images/no-img.png");
            $products = $this->products;
            $data["total"] = "$" . number_format(collect($this->products)->map(function($item) {
                return $item["price"] * $item["quantity"];
            })->sum(), 2, ",", ".");
            $data["html"] = collect($this->products)->map(function($item, $key) use ($no_img, $request) {
                $html = "";
                $newRequest = new \Illuminate\Http\Request();
                $product = Product::one($request, $key);
                $newRequest->replace(['use' => $product["use"]]);
                $stock = intval((new BasicController)->soap($newRequest));
                $style = "background-color: #f34423; color: #ffffff;";
                if ($stock > $product["stock_mini"]) {
                    $style = "background-color: #73e831; color: #111111;";
                } else if ($stock <= $product["stock_mini"] &&  $stock > 0) {
                    $style = "background-color: #fdf49f; color: #111111;";
                }
                $price = $product["priceNumber"] * $item["quantity"];
                $price = number_format($price, 2, ",", ".");
                $img = asset($product["images"][0]);
                $html .= "<tr style='$style'>";
                    $html .= "<td><img src='{$img}' alt='{$product["name"]}' onerror=\"this.src='{$no_img}'\" class='w-100'/></td>";
                    $html .= "<td>";
                        if (isset($product["code"]))
                            $html .= "<p class=\"mb-0 product--code\">{$product["code"]}</p>";
                        if (isset($product["brand"]))
                            $html .= "<p class=\"mb-0 product--for\">{$product["brand"]}</p>";
                        $html .= "<p>{$product["name"]}</p>";
                    $html .= "</td>";
                    //$html .= "<td class='text-center'>" . $product["cantminvta"] . "</td>";
                    $html .= "<td class='text-right'>" . $product["price"] . "</td>";
                    $html .= "<td class='text-center'>" . $item["quantity"] . "</td>";
                    if(auth()->guard('web')->user()->isShowQuantity())
                        $html .= "<td class='text-center'>" . $stock . "</td>";
                    $html .= "<td class='text-right' style='white-space: nowrap;'>$ " . $price . "</td>";
                $html .= "</tr>";
                return $html;
            })->join("");
            if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            return view('page.mobile', compact('data'));
        }
        $elements = $request->all();
        $rules = [
            "transport" => "required"
        ];
        $validator = Validator::make($elements, $rules);
        if ($validator->fails())
            return json_encode(["error" => 1, "msg" => "Revise los datos."]);
        $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        if (empty($this->products))
            return json_encode(["error" => 1, "msg" => "Sin productos en el pedido."]);
        if (is_array($request->transport))
            $transport = collect(Transport::one($request->transport[0], "code"))->toArray();
        else
            $transport = collect(Transport::one($request->transport, "code"))->toArray();
        $data = [
            'transport' => $transport,
            'obs' => empty($request->obs) ? null : $request->obs
        ];
        //try {
            if (!session()->has('accessADM')) {
                $codCliente = (empty(\Auth::user()->docket) || \Auth::user()->test) ? "PRUEBA" : \Auth::user()->docket;
                $codVendedor = 88;// DIRECTA-Zona Centro
                if (!empty(\Auth::user()->uid)) { // Si contiene información, es un cliente
                    $data['client_id'] = \Auth::user()->id;
                    $data['client'] = collect(Client::one(\Auth::user()->uid))->toArray();
                    $data['seller'] = $data['client']["vendedor"];
                    $codVendedor = $data['seller']['code'];
                } else if ($request->session()->has('nrocta_client') && $codCliente != "PRUEBA") { // Si pasa esto, lo hizo Ventor y busco información del Cliente
                    $client = Client::one($request->session()->get('nrocta_client'), "nrocta");
                    $codCliente = $client->nrocta;
                    $data['client'] = collect($client)->toArray();
                    $data['seller'] = $data['client']['vendedor'];
                }
            } else {
                $codCliente = session()->get('accessADM')->docket;
                $data['client_id'] = session()->get('accessADM')->id;
                $data['client'] = collect(Client::one(session()->get('accessADM')->uid))->toArray();
                $data['seller'] = $data['client']["vendedor"];
                $codVendedor = $data['seller']['code'];
            }
            $data['products'] = collect($this->products)->map(function($item, $key) use ($request) {
                $product = Product::one($request, $key);
                return ["product" => $product, "price" => $item["price"], "quantity" => $item["quantity"]];
            })->toArray();
            if (session()->has('accessADM'))
                $cart = Cart::last(session()->get('accessADM'));
            else
                $cart = Cart::last();
            $orderTotal = Order::count() + 1;
            $data["uid"] = $orderTotal;
            $order = Order::create($data);
            session(['order' => $order]);
            $cart->fill(["uid" => $order->_id]);
            $cart->save();
            Ticket::create([
                "type" => 3,
                "table" => "cart",
                "table_id" => $cart->id,
                'obs' => '<p>Se modificó el valor de "uid" de [] <strong>por</strong> [' . htmlspecialchars($order->_id) . ']</p>',
                'user_id' => \Auth::user()->id
            ]);
            $request->session()->forget('cart');

            ///////////////////
            $fecha = date("Ymd-His");
            $traCod = str_pad($transport["code"], 2, "0", STR_PAD_LEFT);
            $title = "Pedido {$codVendedor}-{$codCliente}-{$orderTotal}-{$fecha} Cliente {$codCliente}";
            $order->fill(["title" => $title]);
            $order->save();

            $mensaje = [];
            $mensaje[] = "<&TEXTOS>{$order->obs}</&TEXTOS>";
            $mensaje[] = "<&TRACOD>{$traCod}|{$transport["description"]} {$transport["address"]}</&TRACOD>";
            
            $to = [env('MAIL_TO')];
            if (true) {
                $toArray[] = 'sebastianevillarreal@gmail.com';
                $toArray[] = 'corzo.pabloariel@gmail.com';
                if ($codCliente != "PRUEBA") {
                    $toArray[] = 'pedidos.ventor@gmx.com';
                    $toArray = array_reverse($toArray);
                }
            }
            $to = array_shift($toArray);
    
            $email = Email::create([
                'use' => 0,
                'subject' => $title,
                'body' => implode("", $mensaje),
                'from' => env('MAIL_BASE'),
                'to' => $to
            ]);
            try {
                Mail::to($to)
                    ->send(
                        new OrderMail(
                            $mensaje,
                            $title,
                            Excel::download(
                                new OrderExport($order->_id),
                                    'PEDIDO.xls'
                                )->getFile(), ['as' => 'PEDIDO.xls'])
                );
                $email->fill(["sent" => 1]);
                $email->save();
                ///////////////
                if (env('APP_ENV') == 'production') {
                    if (isset($order['client']['direml']) && $codCliente != "PRUEBA")
                        $to = $order['client']['direml'];
                    else
                        $to = \Auth::user()->email;
                } else 
                    $to = env('MAIL_TO');
                if (!empty($to)) {
                    $html = \View::make("mail.order_products", ["order" => $order])->render();
                    $email = Email::create([
                        'use' => 0,
                        'subject' => "Pedido {$orderTotal}-{$fecha}",
                        'body' => $html,
                        'from' => env('MAIL_BASE'),
                        'to' => $to
                    ]);
                    $subject = "Pedido {$orderTotal} / " . date("d-m-Y H:i");
                    if (env('APP_ENV') == 'local') {
                        if (isset($order['client']['direml']) && $codCliente != "PRUEBA")
                            $subject .= " - " . $order['client']['direml'];
                        else
                            $subject .= " - " . \Auth::user()->email;
                    }
                    try {
                        Mail::to($to)
                            ->send(
                                new BaseMail(
                                    $subject,
                                    'Lista de productos.',
                                    $html)
                            );
                        $email->fill(["sent" => 1]);
                        $email->save();
                    } catch (\Throwable $th) {
                        $email->fill(["error" => 1]);
                        $email->save();
                    }
                }
                ///////////////
                return json_encode(["error" => 0, "success" => true, "order" => $order, "msg" => "Pedido enviado"]);
            } catch (\Throwable $th) {
                $email->fill(["error" => 1]);
                $email->save();

                return response()->json([
                    "error" => 1,
                    "mssg" => "Ocurrió un error."
                ], 200);
            }
        /*} catch (\Throwable $th) {
            dd($th);
            return json_encode(["error" => 1, "msg" => "Ocurrió un error"]);
        }*/
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
