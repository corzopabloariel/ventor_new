<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApplicationImport;

class Application extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'applications';
    protected $primaryKey = '_id';

    protected $fillable = [
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

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'status' => 'bool'
    ];

    public static function create($attr) {
        $code = str_replace(" " , "_", $attr["sku"]);
        $model = self::find($code);
        if (!$model) {
            $model = new self;
            $model->_id = $code;
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

    public static function updateCollection(Bool $fromCron = false) {

        $model = new self;
        $source = implode('/', [public_path(), 'file', 'LISTA DE PRECIOS ARMES TRICO.xlsx']);
        if (file_exists($source)) {

            self::truncate();
            ApplicationTmp::truncate();
            Excel::import(new ApplicationImport, $source);

            $tmp = ApplicationTmp::all();
            $tmp->map(function($application) {
                self::create($application->toArray());
            });
            if ($fromCron) {

                return responseReturn(true, 'Productos insertados: '.self::count());

            }

            return responseReturn(false, 'Productos insertados: '.self::count());
        }

        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }

        return responseReturn(true, 'Archivo no encontrado', 1, 400);
    }
}
