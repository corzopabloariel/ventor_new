<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Newness extends Model
{
    use SoftDeletes;

    protected $table = "news";

    protected $fillable = [
        'order',
        'file',
        'image',
        'name',
    ];

    protected $casts = [
        'image' => 'array',
        'file' => 'array'
    ];

    public static function gets($limit)
    {
        if (!empty($limit))
            $elements = self::orderBy("order")->limit($limit)->get();
        else
            $elements = self::orderBy("order")->get();
        $value = collect($elements)->map(function($x) {
            $img = $file = $name = null;
            $name = $x->name;
            if (isset($x->image["i"]))
                $img = $x->image["i"];
            if (isset($x->file["i"])/* && \Auth::guard('web')->check()*/)
                $file = $x->file["i"];
            return ["image" => $img, "name" => $name, "file" => $file];
        })->toArray();
        return $value;
    }
}
