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
        $date = strtotime($date);
        $this->dateStart = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
    }

    public function setDateEnd($date)
    {
        $date = strtotime($date);
        $this->dateEnd = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
    }

    private function others($products, Request $request)
    {
        if ($request->has("type") && $request->get('type') == "liquidacion") {
            $products = $products->where("liquidacion", "!=", "N");
        }
        if ($request->has("type") && $request->get('type') == "nuevos") {
            self::setDateStart($request->get('start'));
            self::setDateEnd($request->get('end'));
            $products = $products->whereBetween('fecha_ingr', [$this->dateStart, $this->dateEnd]);
        }
        return $products;
    }
    private function _return($products, $productsWBrand = null, Request $request)
    {
        $brands = empty($productsWBrand) ? self::getBrands($products) : self::getBrands($productsWBrand);
        $products = $products->paginate(36);
        $markup = $request->has("markup") ? $request->get("markup") : 0;
        session(['markup' => $markup]);
        return [
            "products" => ProductResource::collection($products),
            "total" => $products->total(),
            "brands" => $brands
        ];
    }
    private function getBrands($products)
    {
        $brands = (clone $products)
            ->select('web_marcas')
            ->distinct()
            ->get();
        $brands = $brands->toArray();
        $brands = array_unique(array_merge(...$brands));
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
        $products = new Product();
        $products = self::others($products, $request);
        if ($withPaginate)
            return self::_return($products, null, $request);
        return $products;
    }
    public function index_search(Request $request, String $search, $products = null, Bool $withPaginate = true)
    {
        if (empty($products))
            $products = self::index($request, false);
        $search_code = str_replace("_", "|", $search);
        $search = explode("_", strtoupper($search));
        $products = $products->where(function ($q) use ($search) {
            foreach ($search as $value) {
                $q->orWhere("search", "LIKE", "%{$value}%");
                $q->where("search", "LIKE", "%{$value}%");
            }
        });
        if ($withPaginate) {
            $return = self::_return($products, null, $request);
            $return["search"] = $search;
            return $return;
        }
        return $products;
    }
    public function index_brand(Request $request, String $brand, $products = null)
    {
        if (empty($products))
            $products = self::index($request, false);
        $productsWBrand = clone $products;
        $products = $products->where("marca_slug", $brand);
        $return = self::_return($products, $productsWBrand, $request);
        $return["brand"] = $brand;
        return $return;
    }
    public function index_brand_search(Request $request, String $brand, String $search, $products = null)
    {
        if (empty($products))
            $products = self::index($request, false);
        $products = self::index_search($request, $search, null, false);
        return self::index_brand($request, $brand, $products);
    }

    public function part(Request $request, Family $part, Bool $withPaginate = true)
    {
        $parts = collect($part->parts)
            ->map(function($item) {
                return [$item->name];
            })
            ->collapse();
        $products = self::index($request, false)->whereIn("parte", $parts->toArray());
        if ($withPaginate) {
            $return = self::_return($products, null, $request);
            $return["part"] = $part;
            return $return;
        }
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
        $products = self::index($request, false)->where("subparte.code", $subpart->code);
        if ($withPaginate) {
            $return = self::_return($products, null, $request);
            $return["subpart"] = $subpart;
            $return["part"] = $subpart->part;
            return $return;
        }
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
