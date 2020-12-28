<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Order extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';
    protected $primaryKey = '_id';
    protected $fillable = [
        'user_id',
        'client_id',
        'client',
        'transport',
        'seller',
        'products',
        'obs'
    ];
    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        $model->user_id = \Auth::user()->id;
        if (isset($attr['client_id']))
            $model->client_id = $attr['client_id'];
        if (isset($attr['client']))
            $model->client = $attr['client'];
        if (isset($attr['transport']))
            $model->transport = $attr['transport'];
        if (isset($attr['seller']))
            $model->seller = $attr['seller'];
        if (isset($attr['products']))
            $model->products = $attr['products'];
        if (isset($attr['obs']))
            $model->obs = $attr['obs'];
        $model->save();
        return $model;
    }
}
