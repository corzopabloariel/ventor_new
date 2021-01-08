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
use Excel;
use Mpdf\Mpdf;
use PDF;
use App\Exports\OrderExport;

use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;
use App\Mail\OrderMail;

class CartController extends Controller
{
    public $products;
    public function __construct()
    {
        $this->middleware('auth');
        $this->products = [];
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

    public function show(Request $request)
    {
        $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        //
        if (!empty($this->products)) {
            $aux = [];
            foreach ($this->products AS $key => $data) {
                $product = Product::find($key);
                if (!$product) {
                    $product = Product::one($data["product"], "search");
                    $aux[$product["_id"]] = $data;
                    $aux[$product["_id"]]["product"] = $product;
                    $aux[$product["_id"]]["price"] = $data["precio"];
                }
            }
            if (empty($aux)) {
                $this->products = $aux;
                session(['cart' => $this->products]);
            }
        }
        //
        $total = collect($this->products)->map(function($item) {
            return $item["price"] * $item["quantity"];
        })->sum();
        $html = collect($this->products)->map(function($item, $key) {
            $product = Product::find($key);
            $price = $product["precio"] * $item["quantity"];
            $price = number_format($price, 2, ",", ".");
            $html = '<li class="menu-cart-list-item">';
                $html .= "<a href=\"#\" onclick=\"event.preventDefault(); deleteItem(this, '{$key}')\">";
                    $html .= "<i class=\"menu-cart-list-close fas fa-times\"></i>";
                $html .= "</a>";
                $html .= "<div class=\"menu-cart-list-item-content\">";
                    $html .= "<p class=\"cart-show-product__code\">{$product["stmpdh_art"]}</p>";
                    $html .= "<p class=\"cart-show-product__for\">{$product["web_marcas"]}</p>";
                    $html .= "<p class=\"cart-show-product__name\">{$product["stmpdh_tex"]}</p>";
                    $html .= "<p class=\"cart-show-product__price\">{$product->price()} <strong class='ml-2'>x</strong> <input class=\"quantity-cart\" data-id=\"{$key}\" data-price=\"{$product["precio"]}\" min=\"{$product["cantminvta"]}\" step=\"{$product["cantminvta"]}\" type=\"number\" value=\"{$item["quantity"]}\"/> <strong class='mr-2'>=</strong> <span>$ {$price}</span></p>";
                $html .= "</div>";
            $html .= '</li>';
            return $html;
        })->join("");
        return ["html" => "<ul>{$html}</ul>", "total" => $total];
    }

    public function confirm(Request $request)
    {
        if (!$request->session()->has('order')) {
            return \Redirect::route('index');
        }
        if (\Auth::user()->role == "ADM" || \Auth::user()->role == "EMP" || \Auth::user()->role == "VND") {
            if (!$request->session()->has('nrocta_client'))
                return back()->withErrors(['password' => "Seleccione un cliente"]);
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
        return view('page.base', compact('data'));
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

    public function checkout(Request $request)
    {
        if ($request->session()->has('order')) {
            return \Redirect::route('order.success');
        }
        if ($request->method() == "GET") {
            if (\Auth::user()->role == "ADM" || \Auth::user()->role == "EMP" || \Auth::user()->role == "VND") {
                if (!$request->session()->has('nrocta_client'))
                    return back()->withErrors(['password' => "Seleccione un cliente"]);
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
            $data["html"] = collect($this->products)->map(function($item, $key) use ($no_img) {
                $html = "";
                $product = Product::find($key);
                $price = $product["precio"] * $item["quantity"];
                $price = number_format($price, 2, ",", ".");
                $html .= "<tr>";
                    $html .= "<td>" . $product->images(1, $no_img) . "</td>";
                    $html .= "<td>";
                        if (isset($product->stmpdh_art))
                            $html .= "<p class=\"mb-0 product--code\">{$product->stmpdh_art}</p>";
                        if (isset($product->web_marcas))
                            $html .= "<p class=\"mb-0 product--for\">{$product->web_marcas}</p>";
                        $html .= "<p>{$product->stmpdh_tex}</p>";
                    $html .= "</td>";
                    $html .= "<td class='text-center'>" . $product->cantminvta . "</td>";
                    $html .= "<td class='text-right'>" . $product->price() . "</td>";
                    $html .= "<td class='text-center'>" . $item["quantity"] . "</td>";
                    $html .= "<td class='text-right' style='white-space: nowrap;'>$ " . $price . "</td>";
                $html .= "</tr>";
                return $html;
            })->join("");
            return view('page.base', compact('data'));
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
        $transport = collect(Transport::one($request->transport[0], "code"))->toArray();
        $data = [
            'transport' => $transport,
            'obs' => empty($request->obs) ? null : $request->obs
        ];
        try {
            $codCliente = (empty(\Auth::user()->docket) || \Auth::user()->test) ? "PRUEBA" : \Auth::user()->docket;
            $codVendedor = 88;// DIRECTA-Zona Centro
            if (!empty(\Auth::user()->uid)) { // Si contiene información, es un cliente
                $data['client_id'] = \Auth::user()->id;
                $data['client'] = collect(Client::one(\Auth::user()->uid))->toArray();
                $data['seller'] = $data['client']->vendedor;
                $codVendedor = $data['seller']['code'];
            } else if ($request->session()->has('nrocta_client') && $codCliente != "PRUEBA") { // Si pasa esto, lo hizo Ventor y busco información del Cliente
                $client = Client::one($request->session()->get('nrocta_client'), "nrocta");
                $codCliente = $client->nrocta;
                $data['client'] = collect($client)->toArray();
                $data['seller'] = $data['client']['vendedor'];
            }
            $data['products'] = collect($this->products)->map(function($item, $key) {
                $product = Product::one($key);
                return ["product" => $product, "price" => $item["price"], "quantity" => $item["quantity"]];
            })->toArray();
            $cart = Cart::last();
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
            $title = "Pedido {$codVendedor}-{$codCliente}-{$order->_id}-{$fecha} Cliente {$codCliente}";
            $order->fill(["title" => $title]);
            $order->save();

            $mensaje = [];
            $mensaje[] = "<&TEXTOS>{$order->obs}</&TEXTOS>";
            $mensaje[] = "<&TRACOD>{$traCod}|{$transport["description"]} {$transport["address"]}</&TRACOD>";
            
            $to = [env('MAIL_TO')];
            if (env('APP_ENV') == 'production') {
                $to[] = 'sebastianevillarreal@gmail.com';
                if ($codCliente != "PRUEBA")
                    $to[] = 'pedidos.ventor@gmx.com';
            }
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
                        'subject' => "Pedido {$order->_id}-{$fecha}",
                        'body' => $html,
                        'from' => env('MAIL_BASE'),
                        'to' => $to
                    ]);
                    try {
                        Mail::to($to)
                            ->send(
                                new BaseMail(
                                    "Pedido {$order->_id} / " . date("d-m-Y H:i"),
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
        } catch (\Throwable $th) {
            dd($th);
            return json_encode(["error" => 1, "msg" => "Ocurrió un error"]);
        }
    }

    public function add(Request $request)
    {
        $lastCart = Cart::last();
        $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        if (!$request->has('price')) {
            unset($this->products[$request->_id]);
            $valueNew = json_encode($this->products);
            $valueOld = $lastCart->data;
            $cart = Cart::create(["data" => $this->products]);
            if (gettype($valueNew) == "array")
                $valueNew = json_encode($valueNew);
            if (gettype($valueOld) == "array")
                $valueOld = json_encode($valueOld);
            if ($valueOld != $valueNew) {
                Ticket::create([
                    "type" => 3,
                    "table" => "cart",
                    "table_id" => $cart->id,
                    'obs' => '<p>Se modificó el valor de "data" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }
            $total = collect($this->products)->map(function($item) {
                return $item["price"] * $item["quantity"];
            })->sum();
            session(['cart' => $this->products]);
            return json_encode(["error" => 0, "success" => true, "total" => $total, "elements" => count($this->products)]);
        }
        $elements = $request->all();
        $rules = [
            "price" => "required|numeric",
            "_id" => "required",
            "quantity" => "required|numeric"
        ];
        $validator = Validator::make($elements, $rules);
        $product = Product::one($request->_id);
        if ($validator->fails())
            return json_encode(["error" => 1, "msg" => "Revise los datos."]);
        if (!isset($this->products[$request->_id])) {
            $this->products[$request->_id] = [];
            $this->products[$request->_id]["product"] = $product;
            $this->products[$request->_id]["price"] = 0;
            $this->products[$request->_id]["quantity"] = 0;
        }

        $this->products[$request->_id]["price"] = $request->price;
        $this->products[$request->_id]["quantity"] = $request->quantity;
        $val = json_encode($this->products);
        $cart = Cart::create(["data" => $this->products]);
        if (!$lastCart) {
            Ticket::create([
                "type" => 1,
                "table" => "cart",
                "table_id" => $cart->id,
                "obs" => "<p>Se agregó elementos al carrito: [{$val}]</p>",
                'user_id' => \Auth::user()->id
            ]);
        } else {
            $valueNew = $val;
            $valueOld = $lastCart->data;
            if (gettype($valueNew) == "array")
                $valueNew = json_encode($valueNew);
            if (gettype($valueOld) == "array")
                $valueOld = json_encode($valueOld);
            if ($valueOld != $valueNew) {
                Ticket::create([
                    "type" => 3,
                    "table" => "cart",
                    "table_id" => $cart->id,
                    'obs' => '<p>Se modificó el valor de "data" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }
        }
        session(['cart' => $this->products]);
        $return = ["error" => 0, "success" => true, "msg" => "Elemento agregado.", "total" => count($this->products)];
        if ($request->has('withTotal')) {
            $return["totalPrice"] = collect($this->products)->map(function($item) {
                return $item["price"] * $item["quantity"];
            })->sum();
        }
        return json_encode($return);
    }
}
