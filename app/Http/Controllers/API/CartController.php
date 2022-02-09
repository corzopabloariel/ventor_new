<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartProductResource;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->isJson()) {

            return response(
                array(
                    'error'     => false,
                    'status'    => 202,
                    'message'   => 'Listado de pedidos'
                ),
                202
            );

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
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartRequest $request)
    {

        return Cart::createOrUpdate($request);

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $userId) {

        return Cart::one($request, $userId);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CartRequest $request, int $userId) {

        return Cart::createOrUpdate($request, $userId);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $cart) {

        $element = Cart::find($cart);
        if ($element) {

            $element->delete();
            return response(
                array(
                    'error'     => false,
                    'status'    => 205,
                    'message'   => 'Carrito eliminado',
                    'action'    => $element
                ),
                205
            );

        }
        return response(
            array(
                'error'     => true,
                'status'    => 404,
                'message'   => 'Carrito no encontrado'
            ),
            404
        );

    }

    /**
     * Total.
     *
     */
    public function products(Request $request, $userId, int $type) {

        $user = User::find($userId);
        $cart = $user->lastCart;
        if ($cart) {

            $elements = $type == 0 ? $cart->products : $cart->quantity;

        } else {

            $elements = $type == 0 ? 0 : array();

        }
        return
        array(
            'error'     => false,
            'status'    => 205,
            'message'   => 'OK',
            'element'   => $type == 2 ? CartProductResource::collection($elements) : $elements,
            'cartId'    => $cart->id ?? null
        );

    }
    public function product(Request $request, User $user) {

        if ($request->isJson()) {

            $cart = $user->lastCart;
            if ($cart) {

                $code = $request->code;
                $product = collect($cart->products)->first(function ($value, $key) use ($code) {
                    return $value['product'] == $code;
                });
                if ($product) {

                    return response(
                        array(
                            'error'     => false,
                            'status'    => 205,
                            'message'   => '',
                            'element'   => $product
                        ),
                        205
                    );

                }
                return response(
                    array(
                        'error'     => false,
                        'status'    => 404,
                        'message'   => 'Producto no encontrado'
                    ),
                    404
                );

            } else {

                return response(
                    array(
                        'error'     => false,
                        'status'    => 404,
                        'message'   => 'Carrito no encontrado'
                    ),
                    404
                );

            }

        } else {

            return response(
                array(
                    'error'     => true,
                    'status'    => 401,
                    'message'   => 'Sin autorización'
                ),
                401
            );

        }

    }
}
