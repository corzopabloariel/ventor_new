<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;

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
        $name = isset($args[0]) ? $args[0] : null;
        $brand = isset($args[1]) ? $args[1] : null;
        $data = self::where("name_slug", $name)->first();
        $products = $marcas = collect([]);
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
                $marcas = $marcas->mergeRecursive((clone $productsFilter)->select('web_marcas')
                    ->distinct()
                    ->get())
                    ->unique()
                    ->toArray();
                if (!empty($brand)) {
                    $productsFilter = $productsFilter->where("marca_slug", $brand);
                }
                $products = $products
                    ->mergeRecursive(
                        $productsFilter
                            ->orderBy("parte")
                            ->orderBy("subparte.code")
                            ->orderBy("web_marcas")
                            ->get()
                        );
            }
        } else {
            $products = new Product;
            if (!empty($search)) {
                $products = $products->where(function ($q) use ($searchValues) {
                    foreach ($searchValues as $value) {
                        $q->orWhere("stmpdh_tex", "LIKE", "%{$value}%");
                    }
                });
            }
            $marcas = collect((clone $products)->select('web_marcas')
                ->distinct()
                ->get())
                ->unique()
                ->toArray();
            if (!empty($brand)) {
                $products = $products->where("marca_slug", $brand);
            }
            $products = $products
                ->orderBy("parte")
                ->orderBy("subparte.code")
                ->orderBy("web_marcas");
        }
        $products = $products->paginate((int) $paginate);
        $marcas = collect($marcas)->map(function ($item, $key) {
            return ["name" => $item[0], "slug" => Str::slug($item[0])];
        })->sortBy("name")->toArray();
        return ["products" => $products, "brand" => $marcas];
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
}
