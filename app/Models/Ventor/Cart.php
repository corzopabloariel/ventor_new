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
        if (!$model) {
            $model = new self;
            $model->user_id = isset($attr["user_id"]) ? $attr["user_id"] : \auth()->guard('web')->user()->id;
        }
        $model->data = $attr['data'];
        $model->save();
        return $model;
    }

    public static function add(Request $request) {
        if (empty($request->all())) {
            $products = self::products($request, null);
            return self::show($request, $products);
        }
        $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json"
        );
        if (empty($products)) {
            $products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        }
        // Quito producto del array
        if (!$request->has('price')) {
            $lastProduct = $products;
            unset($products[$request->_id]);
            $total = totalPriceProducts($products);
            return json_encode(["error" => 0, "success" => true, "total" => $total, "elements" => count($products), "cart" => self::show($request, $products, true)]);
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
        $total = 0;
        if ($request->has('withTotal')) {
            $total = totalPriceProducts($products);
        }
        return json_encode(["error" => 0, "success" => true, "msg" => "Elemento agregado.", "total" => $total, "elements" => count($products), "cart" => self::show($request, $products)]);
    }

    public static function show(Request $request, $addProducts = null, $flagDelete = false) {
        if (empty($addProducts) && !$flagDelete) {
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
        if (!empty($products)) {

            $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
            createJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json",
                $products
            );

        }
        if (empty($products) && $flagDelete) {
            $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
            deleteFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json");
        }
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
        $options = null;
        if(\Auth::user()->isShowQuantity()) {
            $cartConfig = \Auth::user()->config->other['cart'] ?? 1;
            $options = '';
            for($i = 1; $i <= $cartConfig; $i++) {

                $count = "0 productos";
                $selected = '';
                $productsCart = readJsonFile("/file/cart_".\Auth::user()->id."-{$i}.json");
                if (!empty($productsCart)) {
                    $count = count($productsCart)." producto".(count($productsCart) > 1 ? "s" : "");
                }
                if(session()->has('cartSelect') && session()->get('cartSelect') == $i) {
                    $selected = 'selected=true';
                }
                $options .= '<option '.$selected.' value="'.$i.'">Carrito #'.$i.' ['.$count.']</option>';
            }
        }

        if (session()->has('accessADM') && !empty($addProducts) && (!$request->has('noticeClient') || $request->has('noticeClient') && $request->get('noticeClient'))) {
            $username = session()->get('accessADM')->username;

            $newRequest = new \Illuminate\Http\Request();
            $newRequest->replace(['reload' => 1, 'message' => 'Se modificó el carrito, se recargará la página', 'username' => $username]);
            (new \App\Http\Controllers\Page\ClientController)->browser($newRequest);
        }
        return ["html" => "<ul class='login'>{$html}</ul>", "options" => $options, "elements" => empty($products) ? 0 : count($products), "products" => $products, "total" => $total, "totalHtml" => $totalHtml];
    }

    public static function empty(Request $request) {
        $html = '<li class="login__user">';
            $html .= "<p class='name text-center'>Sin productos</p>";
        $html .= '</li>';
        $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
        deleteFile(session()->has('accessADM') ?
        "/file/cart_".session()->get('accessADM')->id."-1.json" :
        "/file/cart_".\Auth::user()->id."-{$number}.json");
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }
        return json_encode(["error" => 0, "html" => "<ul class='login'>{$html}</ul>", "success" => true, "total" => 0, "elements" => 0]);
    }

    public static function products(Request $request, $userControl = null, Bool $async = false) {
        $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json"
        );
        $config = \Auth::user()->config;
        $number = empty($config) ? 1 : (empty($config->other) ? 1 : (isset($config->other['cart']) ? $config->other['cart'] : 1));

        if (!empty($products)) {
            $stringFile = public_path() . "/file/log_update.txt";
            // Si existe archivo de actualización
            // Compruebo su última modificación
            // Verifico la existencia de la variable de session
            $updateProducts = true;
            if (file_exists($stringFile)) {
                $timeFile = filemtime($stringFile);
                if ($request->session()->has('timeFile') && $request->session()->get('timeFile') == $timeFile) {
                    $updateProducts = false;
                } else {
                    session(['timeFile' => $timeFile]);
                }
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
            $products = $aux;
            createJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json",
                $products
            );
        }

        // Solo si tiene más de 1 archivo
        if (isset($updateProducts) && $updateProducts) {
            for ($i = 1; $i <= $number; $i++) {
                if ($number == $i) {
                    continue;
                }

                $productsOther = readJsonFile("/file/cart_".\Auth::user()->id."-{$i}.json");
                if (!empty($productsOther)) {
                    $aux = collect($productsOther)->mapWithKeys(function($data, $key) use ($request, $updateProducts) {
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
                    createJsonFile("/file/cart_".\Auth::user()->id."-{$i}.json",
                        $aux
                    );
                }
            }
        }

        return $products;
    }

    public static function checkout(Request $request, $withColor = true) {
        $site = new Site("checkout");
        $site->setRequest($request);
        $data = $site->elements();
        $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json"
        );
        $no_img = asset("images/no-img.png");
        $data["total"] = "$".number_format(totalPriceProducts($products), 2, ",", ".");
        $data['products'] = $products;
        $data["html"] = collect($products)->map(function($item, $key) use ($no_img, $request, $withColor) {
            $style = "";
            $product = $item['product'];
            if ($withColor) {
                $newRequest = new \Illuminate\Http\Request();
                $newRequest->replace(['use' => $product["use"]]);
                $stock = intval((new BasicController)->soap($newRequest));
                $style = "background-color: #f34423; color: #ffffff;";
                if ($stock > $product["stock_mini"]) {
                    $style = "background-color: #73e831; color: #111111;";
                } else if ($stock <= $product["stock_mini"] &&  $stock > 0) {
                    $style = "background-color: #fdf49f; color: #111111;";
                }
            }
            $price = $product["priceNumber"] * $item["quantity"];
            $price = number_format($price, 2, ",", ".");
            $img = $product["images"][0] ?? '';
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
                if($withColor && auth()->guard('web')->user()->isShowQuantity()) {

                    $html .= "<td class='text-center'>" . $stock . "</td>";

                }
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
                if ($request->session()->has('timeFile') && $request->session()->get('timeFile') == $timeFile) {
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

        if (config('app.env') == 'local') {
            return json_encode(['error' => 0, 'success' => true, 'order' => $order, 'msg' => 'Pedido reenviado']);
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
        $number = session()->has('cartSelect') ? session()->get('cartSelect') : 1;
        $products = readJsonFile(session()->has('accessADM') ?
            "/file/cart_".session()->get('accessADM')->id."-1.json" :
            "/file/cart_".\Auth::user()->id."-{$number}.json"
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
        // Dejo solo cantidad e ID de mongo
        $productsDB = collect($products)->mapWithKeys(function($item, $key) use ($request) {
            return [$key => ['price' => $item['price'], 'quantity' => $item['quantity']]];
        })->toArray();
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
                $orderNew['client_id'] = $client->user()->id;
                $orderNew['client'] = collect($client)->toArray();
                $orderNew['seller'] = $orderNew['client']['vendedor'];
            // Prueba total, solo guardo el cliente
            } else if ($request->session()->has('nrocta_client') && $codeCliente == 'PRUEBA') {
                $client = Client::one($request->session()->get('nrocta_client'), 'nrocta');
                $orderNew['client_id'] = $client->user()->id;
                $orderNew['clientTest'] = collect($client)->toArray();
            }

            $cart = self::create([
                'user_id' => $orderNew['client_id'],
                'data' => $productsDB
            ]);
        } else {
            $orderNew['is_test'] = false;
            $codeCliente = $userControl->docket;
            $orderNew['client_id'] = $userControl->id;
            $orderNew['client'] = collect(Client::one($userControl->uid))->toArray();
            $orderNew['seller'] = $orderNew['client']["vendedor"];
            $codeVendedor = $orderNew['seller']['code'];
            $cart = self::create([
                'user_id' => $userControl->id,
                'data' => $productsDB
            ]);
        }

        // Ordeno los productos y lo transformo en un Array de objetos
        $orderNew['products'] = collect($products)->map(function($item, $key) use ($request) {
            return ['product' => $item['product'], 'price' => $item['price'], 'quantity' => $item['quantity']];
        })->toArray();
        $orderNew['uid'] = Order::count() + 1;
        // Guardo el pedido
        $order = Order::create($orderNew);
        session(['order' => $order]);
        // Guardo ID MONGO en el pedido
        $cart->fill(["uid" => $order->_id]);
        $cart->save();
        Ticket::add(3, $cart->id, 'cart', 'Se modificó el valor', ['', $order->_id, 'uid'], true);
        // Elimino variable de sesión
        self::empty($request);
        ///////////////////
        $date = date("Ymd-His");
        $codeTransport = str_pad($transport['code'], 2, '0', STR_PAD_LEFT);
        $title = "Pedido {$codeVendedor}-{$codeCliente}-{$orderNew['uid']}-{$date} Cliente {$codeCliente}";
        // Guardo título del pedido
        $order->fill(["title" => $title]);
        $order->save();
        // Armo mensaje para mail con formato necesario.
        $message = ["<&TEXTOS>{$order->obs}</&TEXTOS>", "<&TRACOD>{$codeTransport}|{$transport['description']} {$transport['address']}</&TRACOD>"];

        if (config('app.env') == 'local' && !config("app.force")) {
            return json_encode(['error' => 0, 'success' => true, 'order' => $order, 'msg' => 'Pedido enviado']);
        }
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
