<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Family;
use App\Models\Part;
use App\Models\Subpart;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    private $dateStart, $dateEnd;

    function __construct() {
        $this->dateStart = Carbon::now()->subMonth();
        $this->dateEnd = Carbon::now()->startOfDay();
    }

    public function setDateStart($date)
    {
        $this->dateStart = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
    }

    public function setDateEnd($date)
    {
        $this->dateEnd = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
    }

    private function others($products)
    {
        if (session()->has('type') && session()->get('type') == "liquidacion") {
            $products = $products->where("liquidacion", "!=", "N");
        }
        if (session()->has('type') && session()->get('type') == "nuevos")
            $products = $products->whereBetween('fecha_ingr', [$this->dateStart, $this->dateEnd]);
        return $products;
    }
    private function _return($products, $productsWBrand = null)
    {
        $brands = empty($productsWBrand) ? self::getBrands($products) : self::getBrands($productsWBrand);
        $products = $products->paginate(36);
        return [
            "products" => ProductResource::collection($products),
            "brands" => $brands
        ];
    }
    private function getBrands($products)
    {
        $brands = $products->map(function ($item) {
            return $item->web_marcas;
        })->toArray();
        
        $brands = array_unique(array_values($brands));
        sort($brands);
        $brands = collect($brands)->map(function ($item, $key) {
            return ["name" => $item, "slug" => Str::slug($item)];
        });
        return $brands;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Bool $withPaginate = true)
    {
        $products = Product::all();
        $products = self::others($products);
        if ($withPaginate)
            return self::_return($products);
        return $products;
    }
    public function index_search(Request $request, String $search, $products = null)
    {
        if (empty($products))
            $products = self::index($request, false);
        $search = str_replace("_", "|", $search);
        $products = collect($products)->filter(function ($item, $key) use ($search) {
            if(preg_match("/($search)/i", $item->stmpdh_tex) === 1) {
                return $item;
            } 
        });
        return self::_return($products);
    }
    public function index_brand(Request $request, String $brand, $products = null)
    {
        if (empty($products))
            $products = self::index($request, false);
        $productsWBrand = clone $products;
        $products = collect($products)->filter(function ($item, $key) use ($brand) {
            if($item->marca_slug == $brand) {
                return $item;
            }
        });
        return self::_return($products, $productsWBrand);
    }
    public function index_brand_search(Request $request, String $brand, String $search, $products = null)
    {
        if (empty($products))
            $products = self::index($request, false);
        $search = str_replace("_", "|", $search);
        $products = collect($products)->filter(function ($item, $key) use ($search) {
            if(preg_match("/($search)/i", $item->stmpdh_tex) === 1) {
                return $item;
            } 
        });
        $productsWBrand = clone $products;
        $products = collect($products)->filter(function ($item, $key) use ($brand) {
            if($item->marca_slug == $brand) {
                return $item;
            }
        });
        return self::_return($products, $productsWBrand);
    }

    public function part(Request $request, Family $part, Bool $withPaginate = true)
    {
        $parts = $part->parts;
        $products = collect($parts)
            ->map(function($item) {
                $products = $item->products();
                $products = self::others($products);
                return $products->get();
            })
            ->collapse();
        if ($withPaginate)
            return self::_return($products);
        return $products;
    }
    public function part_search(Request $request, Family $part, String $search)
    {
        $products = self::part($request, $part, false);
        return self::index_search($request, $search, $products);
    }
    public function part_brand(Request $request, Family $part, String $brand)
    {
        $products = self::part($request, $part, false);
        return self::index_brand($request, $brand, $products);
    }
    public function part_brand_search(Request $request, Family $part, String $brand, String $search)
    {
        $products = self::part($request, $part, false);
        return self::index_brand_search($request, $brand, $search, $products);
    }

    public function subpart(Request $request, $part, Subpart $subpart, Bool $withPaginate = true)
    {
        $products = collect([$subpart])
            ->map(function($item) {
                $products = $item->products();
                $products = self::others($products);
                return $products->get();
            })
            ->collapse();
        if ($withPaginate)
            return self::_return($products);
        return $products;
    }
    public function subpart_search(Request $request, $part, Subpart $subpart, String $search)
    {
        $products = self::subpart($request, $part, $subpart, false);
        return self::index_search($request, $search, $products);
    }
    public function subpart_brand(Request $request, $part, Subpart $subpart, String $brand)
    {
        $products = self::subpart($request, $part, $subpart, false);
        return self::index_brand($request, $brand, $products);
    }
    public function subpart_brand_search(Request $request, $part, Subpart $subpart, String $brand, String $search)
    {
        $products = self::subpart($request, $part, $subpart, false);
        return self::index_brand_search($request, $brand, $search, $products);
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
}
