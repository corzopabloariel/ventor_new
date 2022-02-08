<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Product;

class PartController extends Controller
{
    public function index(Request $request, Family $part)
    {
        if($request->isJson()) {

            $products = $part->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part->name_slug,
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

    public function search(Request $request, Family $part, String $search)
    {
        if($request->isJson()) {

            $products = $part->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part->name_slug,
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

    public function brand(Request $request, Family $part, String $brand)
    {
        if($request->isJson()) {

            $products = $part->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part->name_slug,
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

    public function brand_search(Request $request, Family $part, String $brand, String $search)
    {
        if($request->isJson()) {

            $products = $part->products;
            return Product::gets(
                $request,
                array(
                    'part'      => $part->name_slug,
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
