<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;

    protected $table = 'users_access';
    protected $fillable = [
        'docket',
        'route',
        'create',
        'read',
        'update',
        'delete'
    ];
    protected $casts = [
        'create' => 'bool',
        'read' => 'bool',
        'update' => 'bool',
        'delete' => 'bool'
    ];

    /* ================== */
    public static function create($attr) {

        $model = new self;
        $model->docket = $attr['docket'];
        $model->route = $attr['route'];
        if (isset($attr['create']))
            $model->create = $attr['create'];
        if (isset($attr['read']))
            $model->read = $attr['read'];
        if (isset($attr['update']))
            $model->update = $attr['update'];
        if (isset($attr['delete']))
            $model->delete = $attr['delete'];
        $model->save();
        return $model;

    }
}
