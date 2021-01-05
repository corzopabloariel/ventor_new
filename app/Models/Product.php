<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class Product extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $primaryKey = '_id';
    protected $fillable = [
        'stmpdh_art',
        'use',
        'codigo_ima',
        'stmpdh_tex',
        'usr_stmpdh',
        'precio',
        'web_marcas',
        'cod_subparte',
        'subparte',
        'modelo_anio',
        'parte',
        'cantminvta',
        'fecha_ingr',
        'nro_original',
        'stock_mini',
        'liquidacion',
        'n1',
        'n2',
        'n3',
        'n4',
        'n5',
        'max_ventas'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_ingr'
    ];

    /* ================== */
    public static function removeAll()
    {
        try {
            self::truncate();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getPartAttribute()
    {
        return Part::where("name", $this->parte)->first();
    }

    public function getSubpartAttribute()
    {
        return Subpart::where("code", $this->subparte["code"])->first();
    }

    /* ================== */
    public static function getAll(String $attr = "_id", String $order = "ASC")
    {
        return self::orderBy($attr, $order)->get();
    }

    public static function one(String $value, String $attr = "_id")
    {
        $collection = self::where($attr, $value)->first();
        $codigo_ima = $collection->codigo_ima;
        $name = "/IMAGEN/{$codigo_ima[0]}/{$codigo_ima}";
        $images = ["{$name}.jpg"];
        for ($i = 1; $i <= 10; $i++) {
            if (file_exists(public_path() . "{$name}-{$i}.jpg"))
                $images[] = "{$name}-{$i}.jpg";
        }
        $collection = collect($collection)->merge(['images' => $images]);
        $filtered = collect($collection)->except(['_id', 'updated_at', 'created_at', 'name_slug']);
        return $filtered->toArray();
    }

    public function images(Int $total = 0, $no_img)
    {
        $codigo_ima = $this->codigo_ima;
        $name = "/IMAGEN/{$codigo_ima[0]}/{$codigo_ima}";
        $images = [];
        if (file_exists(public_path() . "{$name}.jpg"))
            $images[] = asset("{$name}.jpg");
        if ($total == 0) {
            for ($i = 1; $i <= 10; $i++) {
                if (file_exists(public_path() . "{$name}-{$i}.jpg"))
                    $images[] = asset("{$name}-{$i}.jpg");
            }
            if (empty($images))
                $images[] = $no_img;
            return $images;
        }
        if (empty($images))
            $images[] = $no_img;
        return "<img src='{$images[0]}' alt='{$this->stmpdh_tex}' onerror=\"this.src='{$no_img}'\" class='w-100'/>";
    }

    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        $model->search = $attr['stmpdh_art'] . " " . $attr['stmpdh_tex'];
        if (isset($attr['stmpdh_art']))
            $model->stmpdh_art = $attr['stmpdh_art'];
        if (isset($attr['use']))
            $model->use = $attr['use'];
        if (isset($attr['codigo_ima']))
            $model->codigo_ima = $attr['codigo_ima'];
        if (isset($attr['stmpdh_tex'])) {
            $model->stmpdh_tex = $attr['stmpdh_tex'];
            $model->name_slug = Str::slug($attr['stmpdh_tex']);
        }
        if (isset($attr['precio']))
            $model->precio = $attr['precio'];
        if (isset($attr['web_marcas'])) {
            $model->web_marcas = $attr['web_marcas'];
            $model->marca_slug = Str::slug($attr['web_marcas']);
        }
        if (isset($attr['subparte'])) {
            $model->subparte = [
                "code" => $attr['cod_subparte'],
                "name" => $attr['subparte']
            ];
        }
        if (isset($attr['parte']))
            $model->parte = $attr['parte'];
        if (isset($attr['modelo_anio']))
            $model->modelo_anio = $attr['modelo_anio'];
        if (isset($attr['cantminvta']))
            $model->cantminvta = $attr['cantminvta'];
        if (isset($attr['fecha_ingr']))
            $model->fecha_ingr = $attr['fecha_ingr'];
        if (isset($attr['nro_original']))
            $model->nro_original = $attr['nro_original'];
        if (isset($attr['stock_mini']))
            $model->stock_mini = $attr['stock_mini'];
        if (isset($attr['liquidacion']))
            $model->liquidacion = $attr['liquidacion'];
        if (isset($attr['max_ventas']))
            $model->max_ventas = $attr['max_ventas'];
        $model->save();

        return $model;
    }

    public function price() {
        $precio = $this->precio;
        if(session()->has('markup') && session()->get('markup') != "costo") {
            $discount = auth()->guard('web')->user()->discount / 100;
            $precio += $discount * $precio;
        }
        return "$ " . number_format($precio, 2, ".", ".");
    }
}
