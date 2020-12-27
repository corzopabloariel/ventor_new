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
    public static function data($request, $args, $paginate, $search = null)
    {
        $name = $args[1];
        $brand = isset($args[2]) ? $args[2] : null;
        $subpart = self::where("name_slug", $name)->first();
        if (!$subpart)
            return null;
        if (!empty($search)) {
            $search = str_replace("_", " ", $search);
            $searchValues = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
        }
        $products = Product::where("subparte.code", $subpart->code)
            ->where("subparte.name", $subpart->name);
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
            ->orderBy("web_marcas")
            ->paginate((int) $paginate);
        $marcas = collect($marcas)->map(function ($item, $key) {
            return ["name" => $item[0], "slug" => Str::slug($item[0])];
        })->sortBy("name")->toArray();
        return ["products" => $products, "brand" => $marcas];
    }
}
