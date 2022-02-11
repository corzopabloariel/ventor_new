<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Product;
use App\Models\Subpart;

class SubPartController extends Controller
{
    public function index(Request $request, $part, Subpart $subpart)
    {
        if($request->isJson()) {

            $products = $subpart->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part,
                    'subpart'   => $subpart->name_slug,
                    'products'  => $products
                )
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

    public function search(Request $request, $part, Subpart $subpart, String $search)
    {
        if($request->isJson()) {

            $products = $subpart->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part,
                    'subpart'   => $subpart->name_slug,
                    'products'  => $products,
                    'search'    => $search
                )
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

    public function brand(Request $request, $part, Subpart $subpart, String $brand)
    {
        if($request->isJson()) {

            $products = $subpart->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part,
                    'subpart'   => $subpart->name_slug,
                    'products'  => $products,
                    'brand'     => $brand
                )
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

    public function brand_search(Request $request, $part, Subpart $subpart, String $brand, String $search)
    {
        if($request->isJson()) {

            $products = $subpart->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part,
                    'subpart'   => $subpart->name_slug,
                    'products'  => $products,
                    'brand'     => $brand,
                    'search'    => $search
                )
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
}