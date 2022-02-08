<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
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

        if($request->isJson()) {

            return Product::gets($request);

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brands(Request $request) {

        if($request->isJson()) {

            return Product::onlyBrands($request);

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
    /** */
    public function price(Request $request) {

        if ($request->isJson()) {

            return Product::price($request);

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
    /** */
    public function stock(Request $request) {

        if ($request->isJson()) {

            return Product::stock($request);

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

        if ($request->isJson()) {

            return Product::one($request, $request->code);

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
