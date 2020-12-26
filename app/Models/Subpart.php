<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subpart extends Model
{
    use HasFactory;
    protected $table = "subparts";
    protected $fillable = [
        'code',
        'name',
        'name_slug',
        'part_id',
        'family_id'
    ];

    public static function removeAll()
    {
        try {
            self::truncate();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public static function data($request, $args, $paginate)
    {
        $name = $args[1];
        $brand = isset($args[2]) ? $args[2] : null;
        $subpart = self::where("name_slug", $name)->first();
        if (!$subpart)
            return null;
        if (empty($brand)) {
            $products = Product::where("subparte.code", $subpart->code)
                ->where("subparte.name", $subpart->name)
                ->paginate((int)$paginate);
            $marcas = Product::select('web_marcas')
                ->where("subparte.code", $subpart->code)
                ->where("subparte.name", $subpart->name)
                ->orderBy("web_marcas")
                ->distinct()
                ->get()
                ->toArray();
        } else {
            $products = Product::where("subparte.code", $subpart->code)
                ->where("subparte.name", $subpart->name)
                ->where("marca_slug", $brand)
                ->paginate((int)$paginate);
            $marcas = Product::select('web_marcas')
                ->where("subparte.code", $subpart->code)
                ->where("subparte.name", $subpart->name)
                ->distinct()
                ->get()
                ->toArray();
        }
        $marcas = collect($marcas)->map(function ($item, $key) {
            return ["name" => $item[0], "slug" => Str::slug($item[0])];
        })->sortBy("name")->toArray();
        return ["products" => $products, "brand" => $marcas];
    }
}
