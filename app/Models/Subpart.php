<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    public function products()
    {
        return Product::where("subparte.code", $this->code);
    }
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
        $dateStart = Carbon::now()->subMonth();
        $dateEnd = Carbon::now()->startOfDay();
        if (auth()->guard('web')->check()) {
            if (!empty(auth()->guard('web')->user()->start))
                $dateStart = Carbon::createFromDate(date("Y", strtotime(auth()->guard('web')->user()->start)), date("m", strtotime(auth()->guard('web')->user()->start)), date("d", strtotime(auth()->guard('web')->user()->start)));
            if (!empty(auth()->guard('web')->user()->end))
                $dateEnd = Carbon::createFromDate(date("Y", strtotime(auth()->guard('web')->user()->end)), date("m", strtotime(auth()->guard('web')->user()->end)), date("d", strtotime(auth()->guard('web')->user()->end)));
        }

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
                    $q->orWhere("stmpdh_art", "LIKE", "%{$value}%");
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
        if ($paginate == 0) {
            return ["products" => $products->get()];
        } else {
            $products = $products->paginate((int) $paginate);
            $marcas = collect($marcas)->map(function ($item, $key) {
                return ["name" => $item[0], "slug" => Str::slug($item[0])];
            })->sortBy("name")->toArray();
            return ["products" => $products, "brand" => $marcas];
        }
    }

    public function getRouteKeyName()
    {
        return 'name_slug';
    }
}
