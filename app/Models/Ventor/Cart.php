<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Page\BasicController;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Site;
use App\Models\Product;
use App\Models\Order;
use App\Models\Email;
use App\Models\Client;
use App\Models\Transport;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = "cart";
    protected $fillable = [
        'uid',
        'data',
        'user_id',
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function getName() {
        return 'cart';
    }
    /* ================== */
    public static function create($attr)
    {
        $model = self::where("user_id", isset($attr["user_id"]) ? $attr["user_id"] : \auth()->guard('web')->user()->id)->whereNull("uid")->first();
        if (!$model)
            $model = new self;
        $model->data = $attr['data'];
        $model->user_id = isset($attr["user_id"]) ? $attr["user_id"] : \auth()->guard('web')->user()->id;
        $model->save();
        return $model;
    }
    public static function last($user = null, $withUid = false)
    {
        if (empty($user))
            return self::where("user_id", \auth()->guard('web')->user()->id)->whereNull("uid")->first();

        return $withUid ? self::where("user_id", $user->id)->orderBy('id', 'DESC')->first() : self::where("user_id", $user->id)->whereNull("uid")->first();
    }

    public static function add(Request $request) {
        if (empty($request->all())) {
            $products = self::products($request, null);
            return self::show($request, $products);
        }
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id.".json" :
            "/file/cart_".\Auth::user()->id.".json"
        );
        if (empty($products)) {
            $products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        }
        $lastCart = self::last(session()->has('accessADM') ?
            session()->get('accessADM') :
            null
        );
        // Quito producto del array
        if (!$request->has('price')) {
            unset($products[$request->_id]);
            $cart = self::create(["data" => $products]);
            Ticket::add(3, $cart->id, 'cart', 'Se eliminó un producto y modificó el valor', [$lastCart->data, $products, 'data'], false);
            $total = totalPriceProducts($products);
            return json_encode(["error" => 0, "success" => true, "total" => $total, "elements" => count($products), "cart" => self::show($request, $products)]);
        }

        $elements = $request->all();
        $rules = [
            "price" => "required|numeric",
            "_id" => "required",
            "quantity" => "required|numeric"
        ];
        $validator = Validator::make($elements, $rules);
        // Compruebo última actualización del registro
        $product = Product::one($request, $request->_id);
        if ($validator->fails())
            return json_encode(["error" => 1, "msg" => "Revise los datos."]);
        if (!isset($products[$request->_id])) {
            $products[$request->_id] = [];
            $products[$request->_id]["product"] = $product;
            $products[$request->_id]["price"] = 0;
            $products[$request->_id]["quantity"] = 0;
        }

        $products[$request->_id]["price"] = $product["priceNumber"];
        $products[$request->_id]["quantity"] = $request->quantity;
        $productsObj = json_encode($products);
        $dataCart = ["data" => $products];
        if (session()->has('accessADM'))
            $dataCart["user_id"] = session()->get('accessADM')->id;
        $cart = self::create($dataCart);
        if (!$lastCart) {
            Ticket::add(1, $cart->id, 'cart', 'Se agregó un producto al carrito', [null, null, 'data'], false);
        } else {
            Ticket::add(3, $cart->id, 'cart', 'Se agregó un producto y modificó el valor', [$lastCart->data, $productsObj, 'data'], false);
        }

        //session(['cart' => $products]);
        $total = 0;
        if ($request->has('withTotal')) {
            $total = totalPriceProducts($products);
        }
        return json_encode(["error" => 0, "success" => true, "msg" => "Elemento agregado.", "total" => $total, "elements" => count($products), "cart" => self::show($request, $products)]);
    }

    public static function show(Request $request, $addProducts = null) {
        if (empty($addProducts)) {
            $products = self::products($request, null, true);
            if (isset($products['async'])) {
                $html = '<li class="login__user" id="asyncProducts">';
                    $html .= "<p class='name text-center'>Sincronizando productos</p>";
                $html .= '</li>';
                return ["html" => "<ul class='login'>{$html}</ul>", "total" => 0, "totalHtml" => '', "async" => 1];
            }
        } else {
            $products = $addProducts;
        }
        createJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id.".json" :
            "/file/cart_".\Auth::user()->id.".json",
            $products
        );
        //
        $total = totalPriceProducts($products);
        $stock = \Auth::user()->isShowQuantity() ? "<span class=\"cart-show-product__stock\"></span>" : "";
        $html = collect($products)->map(function($item, $key) use ($request, $stock) {
            $price = number_format($item["product"]["priceNumber"] * $item["quantity"], 2, ",", ".");
            $html = '<li class="login__user">';
                $html .= "<a href='#' onclick='event.preventDefault(); window.Ventor.deleteItem(\"{$key}\", true);'>";
                    $html .= "<i class=\"menu-cart-list-close fas fa-times\"></i>";
                $html .= "</a>";
                $html .= "<div class='header__cart__element'>";
                    $html .= "<p class='code' data-code='{$item["product"]["use"]}' data-stockmini='{$item["product"]["stock_mini"]}'>{$item["product"]["code"]}</p>";
                    $html .= "<p class='name'>{$item["product"]["name"]}</p>";
                    $html .= "<div class='price' data-price='{$item["product"]["priceNumber"]}'><span>{$item["product"]["price"]}</span><strong>x</strong><input class='number--header form-control form-control-sm' data-id='{$key}' min='{$item["product"]["cantminvta"]}' step='{$item["product"]["cantminvta"]}' type='number' value='{$item["quantity"]}'/><strong>=</strong><span>$ {$price}</span></div>";
                $html .= "</div>";
            $html .= '</li>';
            return $html;
        })->join("");
        if (empty($html)) {
            $html = '<li class="login__user">';
                $html .= "<p class='name text-center'>Sin productos</p>";
            $html .= '</li>';
        }
        $cartButtons = "<div class='login__buttons'>";
            $cartButtons .= "<button class='button__cart button__cart--clear'>limpiar pedido</button>";
            $cartButtons .= "<button class='button__cart button__cart--end'>finalizar pedido</button>";
        $cartButtons .= "</div>";
        $totalHtml = empty($total) ? '' : "<p class='login__cart__total'>total<span>$ ".number_format($total, 2, ",", ".")."</span></p>{$cartButtons}";
        return ["html" => "<ul class='login'>{$html}</ul>", "elements" => count($products), "products" => $products, "total" => $total, "totalHtml" => $totalHtml];
    }

    public static function empty(Request $request) {
        $lastCart = self::last();
        $valueNew = json_encode([]);
        if ($lastCart) {

            $data = $lastCart->data;
            $valueLast = collect($data)->map(function($item, $key) {
                if (!isset($item['product'])) {
                    return null;
                }
                return $item['product']['search'].' => Cantidad: '.$item['quantity'].' => Precio: '.$item['price'];
            })->filter(function($value) {
                return !empty($value);
            })->join(' || ');
            Ticket::add(3, $lastCart->id, 'cart', 'Se vació el carrito', [$valueLast, $valueNew, 'productos'], true);
            $lastCart->fill(["data" => []]);
            $lastCart->save();

        } else {

            $cart = Cart::create(["data" => []]);
            Ticket::add(3, $cart->id, 'cart', 'Carrito inicializado', [null, null, null]);

        }
        $html = '<li class="login__user">';
            $html .= "<p class='name text-center'>Sin productos</p>";
        $html .= '</li>';
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }
        return json_encode(["error" => 0, "html" => "<ul class='login'>{$html}</ul>", "success" => true, "total" => 0, "elements" => 0]);
    }

    public static function products(Request $request, $userControl = null, Bool $async = false) {
        //$products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id.".json" :
            "/file/cart_".\Auth::user()->id.".json"
        );

        if (!empty($products)) {
            $lastCart = Cart::last($userControl);
            $stringFile = public_path() . "/file/log_update.txt";
            // Si existe archivo de actualización
            // Compruebo su última modificación
            // Verifico la existencia de la variable de session
            $updateProducts = true;
            if (file_exists($stringFile)) {
                $timeFile = filemtime($stringFile);
                if ($request->session()->has('timeFile') && $request->session()->get('timeFile') >= $timeFile) {
                    $updateProducts = false;
                }
                session(['timeFile' => $timeFile]);
            }
            // Si hay que actualizar, lo ejecuto de forma asincrona - para que la apertura no tarde tanto
            if ($async && $updateProducts) {
                return ['async' => 1];
            }
            $aux = collect($products)->mapWithKeys(function($data, $key) use ($request, $updateProducts) {
                $product = $data["product"];
                if ($updateProducts) {
                    $product = Product::one($request, $key);
                    if (empty($product)) {
                        $product = Product::one($request, $data["product"]["search"], "search");
                    }
                    if (empty($product)) {
                        return [0 => 'NO'];
                    }
                }
                return [$product['_id'] => [
                    "product" => $product,
                    "price" => $product["priceNumber"],
                    "quantity" => isset($data["quantity"]) ? $data["quantity"] : 1
                ]];
            })->filter(function($value, $key) {
                return !empty($key);
            })->toArray();
            $productsObj = json_encode($aux);
            $dataCart = ["data" => $aux];
            if (session()->has('accessADM'))
                $dataCart["user_id"] = session()->get('accessADM')->id;
            $cart = self::create($dataCart);
            if (!$lastCart) {
                Ticket::add(1, $cart->id, 'cart', 'Se agregó un producto al carrito', [null, null, 'data'], false);
            } else {
                Ticket::add(3, $cart->id, 'cart', 'Se modificó el valor', [$lastCart->data, $productsObj, 'data'], false);
            }
            //session(['cart' => $aux]);
            $products = $aux;
        }

        return $products;
    }

    public static function checkout(Request $request, $withColor = true) {
        $site = new Site("checkout");
        $site->setRequest($request);
        $data = $site->elements();
        //$products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id.".json" :
            "/file/cart_".\Auth::user()->id.".json"
        );
        $no_img = asset("images/no-img.png");
        $data["total"] = "$".number_format(totalPriceProducts($products), 2, ",", ".");
        $data['products'] = $products;
        $data["html"] = collect($products)->map(function($item, $key) use ($no_img, $request, $withColor) {
            $style = "";
            $product = $item['product'];
            $newRequest = new \Illuminate\Http\Request();
            $newRequest->replace(['use' => $product["use"]]);
            $stock = intval((new BasicController)->soap($newRequest));
            if ($withColor) {
                $style = "background-color: #f34423; color: #ffffff;";
                if ($stock > $product["stock_mini"]) {
                    $style = "background-color: #73e831; color: #111111;";
                } else if ($stock <= $product["stock_mini"] &&  $stock > 0) {
                    $style = "background-color: #fdf49f; color: #111111;";
                }
            }
            $price = $product["priceNumber"] * $item["quantity"];
            $price = number_format($price, 2, ",", ".");
            $img = $product["images"][0];
            $html = "<tr style='$style'>";
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
                if(auth()->guard('web')->user()->isShowQuantity())
                    $html .= "<td class='text-center'>" . $stock . "</td>";
                $html .= "<td class='text-right --one-line'>$ " . $price . "</td>";
            $html .= "</tr>";
            return $html;
        })->join("");
        return $data;
    }

    public static function forward(Request $request, $updatePrice = false) {
        $orderId = $request->order_id__pedidos;
        $order = Order::where("_id", $orderId)->first();
        $title = $order->title;
        $transport = $order->transport;
        $codeTransport = str_pad($transport["code"], 2, "0", STR_PAD_LEFT);
        $obs = isset($order->obs) ? $order->obs : "";
        $message = ["<&TEXTOS>{$obs}</&TEXTOS>","<&TRACOD>{$codeTransport}|{$transport["description"]} {$transport["address"]}</&TRACOD>"];

        if ($updatePrice) {
            $stringFile = public_path() . "/file/log_update.txt";
            // Si existe archivo de actualización
            // Compruebo su última modificación
            // Verifico la existencia de la variable de session
            $updateProducts = true;
            if (file_exists($stringFile)) {
                $timeFile = filemtime($stringFile);
                if ($request->session()->has('timeFile') && $request->session()->get('timeFile') >= $timeFile) {
                    $updateProducts = false;
                }
                session(['timeFile' => $timeFile]);
            }
            $products = collect($order->products)->map(function($item, $key) use ($request, $updateProducts) {
                $product = $item['product'];
                if ($updateProducts) {
                    $product = Product::one($request, $item["product"]["_id"]);
                    if (empty($product)) {
                        $product = Product::one($request, $item["product"]["search"], "search");
                    }
                }
                return ['product' => $product, 'price' => $product['price'], 'quantity' => $item['quantity']];
            })->toArray();
            $cart = self::where('uid', $orderId)->first();
            Ticket::add(3, $cart->id, 'cart', 'Se cambiaron precios de productos y modificó el valor', [$cart->data, $products, 'data'], false);
            $order->fill(['products' => $products]);
            $order->save();
        }
        
        // Envio mails
        $emailOrder = Email::sendOrder($title, $message, $order);
        $emailClient = Email::sendClient($order);

        if ($emailOrder && $emailOrder->sent == 1 && $emailOrder->error == 0) {
            return json_encode(['error' => 0, 'success' => true, 'order' => $order, 'msg' => 'Pedido reenviado']);
        }

        return json_encode([
            'error' => 1,
            'mssg' => 'Ocurrió un error.'
        ]);
    }

    public static function confirm(Request $request, $userControl = null) {
        $elements = $request->all();
        $rules = [
            'transport' => 'required'
        ];
        $validator = Validator::make($elements, $rules);
        if ($validator->fails()) {
            return json_encode(['error' => 1, 'msg' => 'Revise los datos.']);
        }
        //$products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id.".json" :
            "/file/cart_".\Auth::user()->id.".json"
        );
        if (empty($products)) {
            return json_encode(['error' => 1, 'msg' => 'Sin productos en el pedido.']);
        }
        if (is_array($request->transport)) {
            $transport = collect(Transport::one($request->transport[0], 'code'))->toArray();
        } else {
            $transport = collect(Transport::one($request->transport, 'code'))->toArray();
        }
        $orderNew = [
            'transport' => $transport,
            'obs' => empty($request->obs) ? null : $request->obs
        ];

        if (empty($userControl)) {
            $codeCliente = (empty(\Auth::user()->docket) || \Auth::user()->test) ? 'PRUEBA' : \Auth::user()->docket;
            $orderNew['is_test'] = (empty(\Auth::user()->docket) || \Auth::user()->test) ? true : false;
            // DIRECTA-Zona Centro
            $codeVendedor = 88;
            // Si contiene información, es un cliente
            if (!empty(\Auth::user()->uid)) {
                $orderNew['client_id'] = \Auth::user()->id;
                $orderNew['client'] = collect(Client::one(\Auth::user()->uid))->toArray();
                $orderNew['seller'] = $orderNew['client']['vendedor'];
                $codeVendedor = $orderNew['seller']['code'];
            // Si pasa esto, lo hizo Ventor y busco información del Cliente
            } else if ($request->session()->has('nrocta_client') && $codeCliente != 'PRUEBA') {
                $client = Client::one($request->session()->get('nrocta_client'), 'nrocta');
                $codeCliente = $client->nrocta;
                $orderNew['client'] = collect($client)->toArray();
                $orderNew['seller'] = $orderNew['client']['vendedor'];
            // Prueba total, solo guardo el cliente
            } else if ($request->session()->has('nrocta_client') && $codeCliente == 'PRUEBA') {
                $client = Client::one($request->session()->get('nrocta_client'), 'nrocta');
                $orderNew['clientTest'] = collect($client)->toArray();
            }
        } else {
            $orderNew['is_test'] = false;
            $codeCliente = $userControl->docket;
            $orderNew['client_id'] = $userControl->id;
            $orderNew['client'] = collect(Client::one($userControl->uid))->toArray();
            $orderNew['seller'] = $orderNew['client']["vendedor"];
            $codeVendedor = $orderNew['seller']['code'];
        }

        // Ordeno los productos y lo transformo en un Array de objetos
        $orderNew['products'] = collect($products)->map(function($item, $key) use ($request) {
            return ['product' => $item['product'], 'price' => $item['price'], 'quantity' => $item['quantity']];
        })->toArray();
        $cart = Cart::last($userControl);
        $orderNew['uid'] = Order::count() + 1;
        // Guardo el pedido
        $order = Order::create($orderNew);
        session(['order' => $order]);
        // Guardo ID MONGO en el pedido
        $cart->fill(["uid" => $order->_id]);
        $cart->save();
        Ticket::add(3, $cart->id, 'cart', 'Se modificó el valor', ['', $order->_id, 'uid'], true);
        // Elimino variable de sesión
        Cart::empty($request);
        ///////////////////
        $date = date("Ymd-His");
        $codeTransport = str_pad($transport['code'], 2, '0', STR_PAD_LEFT);
        $title = "Pedido {$codeVendedor}-{$codeCliente}-{$orderNew['uid']}-{$date} Cliente {$codeCliente}";
        // Guardo título del pedido
        $order->fill(["title" => $title]);
        $order->save();
        // Armo mensaje para mail con formato necesario.
        $message = ["<&TEXTOS>{$order->obs}</&TEXTOS>", "<&TRACOD>{$codeTransport}|{$transport['description']} {$transport['address']}</&TRACOD>"];
        // Envio mails
        $emailOrder = Email::sendOrder($title, $message, $order);
        $emailClient = Email::sendClient($order, $userControl);

        if ($emailOrder->sent == 1 && $emailOrder->error == 0) {
            return json_encode(['error' => 0, 'success' => true, 'order' => $order, 'msg' => 'Pedido enviado']);
        }

        return json_encode([
            'error' => 1,
            'mssg' => 'Ocurrió un error.'
        ]);
    }
}
