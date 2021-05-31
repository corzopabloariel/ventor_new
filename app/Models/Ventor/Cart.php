<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;
use App\Models\Product;

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
        $products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        if (session()->has('accessADM'))
            $lastCart = self::last(session()->get('accessADM'));
        else
            $lastCart = self::last();
        if (!$request->has('price')) {
            unset($products[$request->_id]);
            $valueNew = json_encode($products);
            $valueOld = $lastCart->data;
            $cart = self::create(["data" => $products]);
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
            $total = collect($products)->map(function($item) {
                return $item["price"] * $item["quantity"];
            })->sum();
            session(['cart' => $products]);
            return json_encode(["error" => 0, "success" => true, "total" => $total, "elements" => count($products), "cart" => self::show($request, $products)]);
        }

        $elements = $request->all();
        $rules = [
            "price" => "required|numeric",
            "_id" => "required",
            "quantity" => "required|numeric"
        ];
        $validator = Validator::make($elements, $rules);
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
        $val = json_encode($products);
        $dataCart = ["data" => $products];
        if (session()->has('accessADM'))
            $dataCart["user_id"] = session()->get('accessADM')->id;
        $cart = self::create($dataCart);
        if (!$lastCart) {
            Ticket::create([
                "type" => 1,
                "table" => "cart",
                "table_id" => $cart->id,
                "obs" => "<p>Se agregó elementos al carrito</p>",
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
                    'obs' => '<p>Se modificó el valor de "data"</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }
        }
        session(['cart' => $products]);
        $total = 0;
        if ($request->has('withTotal')) {
            $total = collect($products)->map(function($item) {
                return $item["price"] * $item["quantity"];
            })->sum();
        }
        return json_encode(["error" => 0, "success" => true, "msg" => "Elemento agregado.", "total" => $total, "elements" => count($products), "cart" => self::show($request, $products)]);
    }

    public static function show(Request $request, $addProducts = null) {
        if (empty($addProducts)) {
            $products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
            //
            if (!empty($products)) {
                if (session()->has('accessADM'))
                    $lastCart = self::last(session()->get('accessADM'));
                else
                    $lastCart = self::last();
                $aux = [];
                foreach ($products AS $key => $data) {
                    try {
                        $product = Product::one($request, $key);
                        if (empty($product)) {
                            $product = Product::one($request, $data["product"]["search"], "search");
                            if (empty($product))
                                continue;
                        }
                        if (!isset($aux[$product["_id"]]))
                            $aux[$product["_id"]] = [];
                        $aux[$product["_id"]] = $data;
                        $aux[$product["_id"]]["product"] = $product;
                        $aux[$product["_id"]]["price"] = $$product["priceNumber"];
                    } catch (\Throwable $th) {}
                }
                $val = json_encode($aux);
                $dataCart = ["data" => $aux];
                if (session()->has('accessADM'))
                    $dataCart["user_id"] = session()->get('accessADM')->id;
                $cart = self::create($dataCart);
                if (!$lastCart) {
                    Ticket::create([
                        "type" => 1,
                        "table" => "cart",
                        "table_id" => $cart->id,
                        "obs" => "<p>Se agregó elementos al carrito</p>",
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
                            'obs' => '<p>Se modificó el valor de "data"</p>',
                            'user_id' => \Auth::user()->id
                        ]);
                    }
                }
                session(['cart' => $aux]);
                $products = $aux;
            }
        } else
            $products = $addProducts;
        //
        $total = collect($products)->map(function($item) {
            return $item["price"] * ((int) $item["quantity"]);
        })->sum();
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
                    $html .= "<div class='price' data-price='{$item["product"]["priceNumber"]}'><span>{$item["product"]["price"]}</span><strong>x</strong><input class='number--header form-control form-control-sm' data-id='{$key}' data-pricenumberstd='{$item["product"]["priceNumber"]}' data-cantminvta='{$item["product"]["cantminvta"]}' data-stock_mini='{$item["product"]["cantminvta"]}' min='{$item["product"]["cantminvta"]}' step='{$item["product"]["cantminvta"]}' type='number' value='{$item["quantity"]}'/><strong>=</strong><span>$ {$price}</span></div>";
                $html .= "</div>";
            $html .= '</li>';
            return $html;
        })->join("");
        $totalHtml = empty($total) ? '' : "<p class='login__cart__total'>total<span>$ ".number_format($total, 2, ",", ".")."</span></p>";
        return ["html" => "<ul class='login'>{$html}</ul>", "total" => $total, "totalHtml" => $totalHtml];
    }
}
