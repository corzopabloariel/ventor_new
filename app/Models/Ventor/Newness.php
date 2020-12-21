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
}
