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
        'uid',
        'user_id',
        'client_id',
        'client',
        'transport',
        'seller',
        'products',
        'title',
        'obs',
        'is_test'
    ];

    protected $casts = [
        'is_test' => 'boolean'
    ];
    public static function data($request, $paginate, $client = null)
    {
        if (empty($client)) {
            $data = self::where("user_id", \Auth::user()->id);
        } else {
            $data = self::where("client.nrocta", $client->nrocta);
        }
        $data = $data->orderBy("_id", "DESC")
                ->paginate((int) $paginate);
        return $data;
    }
    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        $model->user_id = \Auth::user()->id;
        if (isset($attr['uid']))
            $model->uid = $attr['uid'];
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
        $model->is_test = isset($attr['is_test']) ? $attr['is_test'] : false;
        $model->save();
        return $model;
    }

    public function getName() {
        return 'orders';
    }
}
