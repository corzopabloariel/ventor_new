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
        if ($request->isJson()) {

            return Cart::createOrUpdate($request);

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
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $cart) {

        if ($request->isJson()) {

            return Cart::one($request, $cart->lastCart);

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
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CartRequest  $request
     * @param  \App\Models\User  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(CartRequest $request, User $cart) {

        if ($request->isJson()) {

            return Cart::createOrUpdate($request, $cart->lastCart);

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
     * @param  Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $cart) {

        if ($request->isJson()) {

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

    /**
     * Total.
     *
     */
    public function products(Request $request, User $user, int $type) {

        if ($request->isJson()) {

            $cart = $user->lastCart;
            if ($cart) {

                $elements = $type == 0 ? $cart->products : $cart->quantity;

            } else {

                $elements = $type == 0 ? 0 : array();

            }
            return response(
                array(
                    'error'     => false,
                    'status'    => 205,
                    'message'   => 'OK',
                    'element'   => $type == 2 ? CartProductResource::collection($elements) : $elements,
                    'cartId'    => $cart->id ?? null
                ),
                205
            );

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
