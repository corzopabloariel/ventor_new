<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public $products;
    public function __construct()
    {
        $this->middleware('auth');
        $this->products = [];
    }

    public function show(Request $request)
    {
        $html = "";
        $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        foreach($this->products AS $_id => $data) {
            $product = Product::find($_id);
            $html .= '<div class="cart-show-product">';
                $html .= "<p class=\"cart-show-product__code\">{$product["stmpdh_art"]}</p>";
                $html .= "<p class=\"cart-show-product__for\">{$product["web_marcas"]}</p>";
                $html .= "<p class=\"cart-show-product__name\">{$product["stmpdh_tex"]}</p>";
                $html .= "<p class=\"cart-show-product__price\">{$product->price()}</p>";
            $html .= '</div>';
        }
        return $html;
    }

    public function add(Request $request)
    {
        $this->products = $request->session()->has('cart') ? $request->session()->get('cart') : [];
        $elements = $request->all();
        $rules = [
            "price" => "required|numeric",
            "_id" => "required",
            "quantity" => "required|numeric"
        ];
        $validator = Validator::make($elements, $rules);
        if ($validator->fails())
            return json_encode(["error" => 1, "msg" => "Revise los datos."]);
        if (!isset($this->products[$request->_id])) {
            $this->products[$request->_id] = [];
            $this->products[$request->_id]["price"] = 0;
            $this->products[$request->_id]["quantity"] = 0;
        }

        $this->products[$request->_id]["price"] = $request->price;
        $this->products[$request->_id]["quantity"] = $request->quantity;
        $val = json_encode($this->products);
        $lastCart = Cart::last();
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
        return json_encode(["error" => 0, "success" => true, "msg" => "Elemento agregado.", "total" => count($this->products)]);
    }
}
