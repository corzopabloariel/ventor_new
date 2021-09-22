<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationTmp extends Model
{
    use HasFactory;
    protected $table = 'application_tmp';

    protected $fillable = [
        'sku',
        'brand',
        'model',
        'year',
        'type',// DEL - TRAS
        'element',// Array: lado-precio-stock
        'price',
        'status',
        'title',
        'description'
    ];

    protected $casts = [
        'element' => 'array',
        'status' => 'bool'
    ];

    public static function create($attr) {
        //$code = str_replace(" " , "_", $attr["sku"]);
        $model = self::where('sku', $attr["sku"])->first();
        if (!$model) {
            $model = new self;
            $model->sku = $attr["sku"];
        }
        if (isset($attr['brand']))
            $model->brand = $attr['brand'];
        if (isset($attr['model']))
            $model->model = $attr['model'];
        if (isset($attr['year']))
            $model->year = $attr['year'];
        if (isset($attr['type']))
            $model->type = $attr['type'];
        if (isset($attr['element']))
            $model->element = $attr['element'];
        if (isset($attr['price']))
            $model->price = $attr['price'];
        if (isset($attr['status']))
            $model->status = $attr['status'];
        if (isset($attr['title']))
            $model->title = $attr['title'];
        if (isset($attr['description']))
            $model->description = $attr['description'];
        $model->save();

        return $model;
    }
}
