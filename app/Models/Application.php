<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApplicationImport;
use Illuminate\Support\Str;
use App\Models\Product;

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

    public function getDataAttribute() {
        $request = new \Illuminate\Http\Request();
        $elements = collect($this->element)->map(function($item, $key) use ($request) {
            return Product::one($request, $item['code']);
        });
        return $elements;
    }

    public static function create($attr) {
        $code = str_replace("." , "__", $attr["sku"]);
        $code = str_replace(" " , "_", $code);
        $model = self::find($code);
        if (!$model) {
            $model = new self;
            $model->_id = $code;
            $model->sku = $attr["sku"];
        }
        if (isset($attr['brand'])) {
            $model->brand = array(
                'name' => $attr['brand'],
                'slug' => Str::slug($attr['brand'])
            );
        }
        if (isset($attr['model'])) {
            $model->model = array(
                'name' => $attr['model'],
                'slug' => Str::slug($attr['model'])
            );
        }
        if (isset($attr['year']))
            $model->year = $attr['year'];
        if (isset($attr['type']))
            $model->type = $attr['type'];
        if (isset($attr['element'])) {
            if (isset($attr['element']['C'])) {
                $product = Product::find($attr['element']['C']['code']);
                $attr['element']['C']['status'] = $product ? true : false;
            }
            if (isset($attr['element']['A'])) {
                $product = Product::find($attr['element']['A']['code']);
                $attr['element']['A']['status'] = $product ? true : false;
            }
            if (isset($attr['element']['T'])) {
                $product = Product::find($attr['element']['T']['code']);
                $attr['element']['T']['status'] = $product ? true : false;
            }
            $model->element = $attr['element'];
        }
        if (isset($attr['price']))
            $model->price = $attr['price'];
        if (isset($attr['title']))
            $model->title = $attr['title'];
        if (isset($attr['description']))
            $model->description = str_replace("\n", "<br/>", $attr['description']);
        $model->save();

        return $model;
    }


    public static function brands() {
        return self::select('brand')
            ->distinct()
            ->orderBy('brand')
            ->get()
            ->toArray();
    }

    public static function models($brand) {
        return self::select('model')
            ->distinct()
            ->where('brand.slug', $brand)
            ->orderBy('model')
            ->get()
            ->toArray();
    }

    public static function years($elements) {
        return self::select('year')
            ->distinct()
            ->where('brand.slug', $elements[0])
            ->where('model.slug', $elements[1])
            ->orderBy('year')
            ->get()
            ->toArray();
    }

    public static function products($elements) {
        $data = self::
            where('brand.slug', $elements[0])
            ->where('model.slug', $elements[1]);
        if (isset($elements[2])) {
            $data = $data->where('year', $elements[2]);
        }
        $data = $data->orderBy('sku')
            ->get();
        return $data;
    }
}
