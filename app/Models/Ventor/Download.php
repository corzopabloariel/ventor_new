<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order',
        'type',
        'name',
        'image',
        'files'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'image' => 'array',
        'files' => 'array'
    ];

    /* ================== */
    public static function type(String $type)
    {
        return self::where("type", $type);
    }

    public static function gets()
    {
        $elements = self::orderBy("type")->orderBy("order")->get();
        $value = collect($elements)->map(function($item) {
            $img = $files = $name = null;
            $name = $item->name;
            if (isset($item->image["i"]))
                $img = $item->image["i"];
            if ((!empty($item->files) && \Auth::guard('web')->check()) || $item->type == "PUBL") {
                $files = collect($item->files)->map(function($x) {
                    return ["name" => $x["file"]["n"], "file" => isset($x["file"]["i"]) ? $x["file"]["i"] : null];
                })->whereNotNull('file')->toArray();
            }
            return ["image" => $img, "name" => $name, "files" => $files, "type" => $item->type];
        })->toArray();
        $grouped = collect($value)->groupBy(function ($item, $key) {
            return $item['type'];
        })->toArray();
        return $grouped;
    }
}
