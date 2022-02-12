<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Family;
use App\Models\Part;
use App\Models\User;
use App\Models\Subpart;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        return Product::gets($request);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brands(Request $request) {

        return Product::onlyBrands($request);

    }
    /** */
    public function price(Request $request) {

        return Product::price($request);

    }
    /** */
    public function stock(Request $request) {

        return Product::stock($request);

    }
    /**
     * Guardar registro
     *
     * @param  App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        if ($request->isJson()) {

            $validated = $request->validated();
            return Product::createOrUpdate($request, $request->all());

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }
    }

    /**
     * Mostrar un producto: Se cambió a un método patch, aunque no es para esto
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $dataCartProducts = null;
        $product = $request->product;
        $markup = session()->has('markup') ? session()->get('markup') : 'costo';
        if (\Auth::check()) {

            $request = new \Illuminate\Http\Request();
            $request->setMethod('GET');
            $request->request->add(['method' => 'GET']);
            $userId = session()->has('accessADM') ? session()->get('accessADM') :  \Auth::user()->id;
            if ($markup == 'costo') {

                $data['cart'] = Cart::one($request, $userId);

            }
            $dataCartProducts = Cart::products($request, $userId, 0);

        }
        return view(
            'components.public.product',
            array(
                'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['path']) : null,
                'product'   => $product,
                'markup'    => $markup
            )
        )->render();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ProductRequest  $request
     * @param  String  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, String $product)
    {
        if ($request->isJson()) {

            $validated = $request->validated();
            return Product::createOrUpdate($request, $request->all(), false);

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, String $product)
    {
        return Product::erase($request, $product);
    }
}
