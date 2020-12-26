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

    public static function data($request, $args, $paginate)
    {
        $name = $args[0];
        $brand = isset($args[1]) ? $args[1] : null;
        $data = self::where("name_slug", $name)->first();
        $products = $marcas = collect([]);
        foreach ($data->parts() AS $part)
        {
            if (empty($brand)) {
                $products = $products->mergeRecursive(
                    Product::where("parte", $part->name)
                        ->orderBy("parte")
                        ->orderBy("subparte.code")
                        ->orderBy("web_marcas")
                        ->get()
                    );
                $marcas = $marcas->mergeRecursive(Product::select('web_marcas')
                    ->where("parte", $part->name)
                    ->distinct()
                    ->get())
                    ->unique()
                    ->toArray();
            } else {
                $products = $products->mergeRecursive(
                    Product::where("parte", $part->name)
                        ->where("marca_slug", $brand)
                        ->orderBy("parte")
                        ->orderBy("subparte.code")
                        ->get()
                    );
                $marcas = $marcas->mergeRecursive(Product::select('web_marcas')
                    ->where("parte", $part->name)
                    ->distinct()
                    ->get())
                    ->unique()
                    ->toArray();
            }
        }
        $products = $products->paginate($paginate);
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
