<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class EmailMongo extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'emails';
    protected $primaryKey = '_id';

    protected $fillable = [
        'subject',
        'body',
        'from',
        'to',
        'is_order'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'is_order' => 'boolean'
    ];
    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        if (isset($attr['subject']))
            $model->subject = $attr['subject'];
        if (isset($attr['body']))
            $model->body = $attr['body'];
        if (isset($attr['from']))
            $model->from = $attr['from'];
        if (isset($attr['to']))
            $model->to = $attr['to'];
        $model->is_order = isset($attr['is_order']) ? $attr['is_order'] : false;
        $model->save();

        return $model;
    }
}
