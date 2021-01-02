<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Family;
use App\Models\Part;
use App\Models\Subpart;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, String $brand = "", String $search = "")
    {
        return self::search_brand($request, null, null, $brand, $search);
    }
    public function index_search(Request $request, String $search = "")
    {
        return self::index($request, "", $search);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response([
            'product' => new ProductResource($product)
        ], 200);
    }

    public function search(Request $request, $part, Subpart $subpart = null, String $search = "")
    {
        return self::search_brand($request, $part, $subpart, "", $search);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search_brand(Request $request, $part, Subpart $subpart = null, String $brand = "", String $search = "")
    {
        if (empty($part)) {
            $products = Product::all();
        } else {
            if (empty($subpart)) {
                $parts = Family::where("name_slug", $part)->first()->parts;
                $products = collect($parts)->map(function($item) {
                    return $item->products()->get();
                })
                ->collapse();
            } else {
                $products = collect([$subpart])->map(function($item) {
                    return $item->products()->get();
                })
                ->collapse();
            }
        }
        if (!empty($brand)) {
            $products = collect($products)->filter(function ($item, $key) use ($brand) {
                if($item->marca_slug == $brand) {
                    return $item;
                }
            });
        }
        if (!empty($search)) {
            $search = str_replace("_", "|", $search);
            $products = collect($products)->filter(function ($item, $key) use ($search) {
                if(preg_match("/($search)/i", $item->stmpdh_tex) === 1) {
                    return $item;
                } 
            });
        }
        $products = $products->paginate(36);
        return ProductResource::collection($products);
    }
    /*
    if ($request->session()->has('type') && $request->session()->get('type') == "liquidacion") {
                    $productsFilter = $productsFilter->where("liquidacion", "!=", "N");
                }
                if ($request->session()->has('type') && $request->session()->get('type') == "nuevos") {
                    
                    $productsFilter = $productsFilter->where('fecha_ingr', "<=", $dateEnd);
                    $productsFilter = $productsFilter->where('fecha_ingr', ">=", $dateStart);
                }*/
}
