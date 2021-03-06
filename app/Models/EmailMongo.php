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
        'is_order',
        'is_update',
        'is_backup'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'is_order' => 'boolean',
        'is_update' => 'boolean',
        'is_backup' => 'boolean'
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
        $model->is_backup = isset($attr['is_backup']) ? $attr['is_backup'] : false;
        $model->is_update = isset($attr['is_update']) ? $attr['is_update'] : false;
        $model->save();

        return $model;
    }

    public function basic() {
        return $this->belongsTo('App\Models\Email','uid','_id');
    }
}
