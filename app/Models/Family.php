<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;
use Carbon\Carbon;

class Family extends Model
{
    use HasFactory;
    protected $table = "families";
    protected $fillable = [
        'order',
        'name',
        'name_slug',
        'color',
        'icon'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'color' => 'array',
        'icon' => 'array'
    ];

    public function parts()
    {
        return $this->hasMany('App\Models\Part','family_id','id')->get();
    }

    public function subparts()
    {
        $elements = $this->hasMany('App\Models\Subpart','family_id','id')->orderBy("code")->get();
        $value = collect($elements)->map(function($item) {
            return ["name" => $item->name, "slug" => $item->name_slug];
        })->toArray();
        return $value;
    }

    public static function data($request, $args, $paginate, $search = null)
    {
        $dateStart = Carbon::now()->subMonth();
        $dateEnd = Carbon::now()->startOfDay();
        if (auth()->guard('web')->check()) {
            if (!empty(auth()->guard('web')->user()->start))
                $dateStart = Carbon::createFromDate(date("Y", strtotime(auth()->guard('web')->user()->start)), date("m", strtotime(auth()->guard('web')->user()->start)), date("d", strtotime(auth()->guard('web')->user()->start)));
            if (!empty(auth()->guard('web')->user()->end))
                $dateEnd = Carbon::createFromDate(date("Y", strtotime(auth()->guard('web')->user()->end)), date("m", strtotime(auth()->guard('web')->user()->end)), date("d", strtotime(auth()->guard('web')->user()->end)));
        }

        $name = isset($args[0]) ? $args[0] : null;
        $brand = isset($args[1]) ? $args[1] : null;
        $data = self::where("name_slug", $name)->first();
        $products = $marcas = null;
        if (!empty($search)) {
            $search = str_replace("_", " ", $search);
            $searchValues = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
        }
        if ($data)
        {
            foreach ($data->parts() AS $part)
            {
                $productsFilter = Product::where("parte", $part->name);
                if (!empty($search)) {
                    $productsFilter = $productsFilter->where(function ($q) use ($searchValues) {
                        foreach ($searchValues as $value) {
                            $q->orWhere("stmpdh_tex", "LIKE", "%{$value}%");
                        }
                    });
                }
                if ($request->session()->has('type') && $request->session()->get('type') == "liquidacion") {
                    $productsFilter = $productsFilter->where("liquidacion", "!=", "N");
                }
                if ($request->session()->has('type') && $request->session()->get('type') == "nuevos") {
                    
                    $productsFilter = $productsFilter->where('fecha_ingr', "<=", $dateEnd);
                    $productsFilter = $productsFilter->where('fecha_ingr', ">=", $dateStart);
                }
                if ($paginate != 0) {
                    if (!empty($marcas)) {
                        $marcas = $marcas->mergeRecursive((clone $productsFilter)->select('web_marcas')
                            ->distinct()
                            ->get());
                    } else {
                        $marcas = (clone $productsFilter)->select('web_marcas')
                        ->distinct()
                        ->get();
                    }
                }
                if (!empty($brand)) {
                    $productsFilter = $productsFilter->where("marca_slug", $brand);
                }
                if (!empty($products)) {
                    $products = $products
                        ->mergeRecursive(
                            $productsFilter
                                ->orderBy("parte")
                                ->orderBy("subparte.code")
                                ->orderBy("web_marcas")
                                ->get()
                            );
                } else {
                    $products = $productsFilter
                        ->orderBy("parte")
                        ->orderBy("subparte.code")
                        ->orderBy("web_marcas")
                        ->get();
                }
                //if (auth()->guard('web')->check())
                    //session(['products' => $products]);
            }
            $marcas = $marcas->toArray();
            $marcas = array_unique(array_merge(...$marcas));
        } else {
            $products = new Product;
            if (!empty($search)) {
                $products = $products->where(function ($q) use ($searchValues) {
                    foreach ($searchValues as $value) {
                        $q->orWhere("stmpdh_tex", "LIKE", "%{$value}%");
                    }
                });
            }
            if ($request->session()->has('type') && $request->session()->get('type') == "liquidacion") {
                $products = $products->where("liquidacion", "!=", "N");
            }
            if ($request->session()->has('type') && $request->session()->get('type') == "nuevos") {
                
                $products = $products->where('fecha_ingr', "<=", $dateEnd);
                $products = $products->where('fecha_ingr', ">=", $dateStart);
            }
            if ($paginate != 0) {
                $marcas = collect((clone $products)->select('web_marcas')
                    ->distinct()
                    ->get())
                    ->unique()
                    ->toArray();
            }
            if (!empty($brand)) {
                $products = $products->where("marca_slug", $brand);
            }
            $products = $products
                ->orderBy("parte")
                ->orderBy("subparte.code")
                ->orderBy("web_marcas");
            if ($paginate == 0)
                $products = $products->get();
            $marcas = array_unique(array_merge(...$marcas));
        }
        if ($paginate == 0)
            return ["products" => $products];
        else {
            $products = $products->paginate((int) $paginate);
            $marcas = collect($marcas)->map(function ($item, $key) {
                return ["name" => $item, "slug" => Str::slug($item)];
            })->sortBy("name")->toArray();
            return ["products" => $products, "brand" => $marcas];
        }
    }

    public static function gets()
    {
        $elements = self::orderBy("order")->get();
        $value = collect($elements)->map(function($item) {
            $img = $file = $name = null;
            $name = $item->name;
            if (isset($item->icon["i"]))
                $img = $item->icon["i"];
            return [
                "icon" => $img,
                "name" => $name,
                "color" => $item->color,
                "slug" => $item->name_slug,
                "subparts" => $item->subparts()
            ];
        })->toArray();
        return $value;
    }

    public static function colors()
    {
        $elements = self::orderBy("order")->get();
        $value = collect($elements)->map(function($item) {
            $elements = $item->hasMany('App\Models\Subpart','family_id','id')->orderBy("code")->get();
            $arr = [];
            foreach($elements AS $element) {
                $arr[$element->code] = $item->color["color"];
            }
            return $arr;
        })->toArray();
        return array_merge(...$value);
    }
}
