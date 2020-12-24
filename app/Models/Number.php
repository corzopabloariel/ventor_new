<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'province',
        'name',
        'person',
        'internal',
        'email',
        'phone'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'email' => 'array',
        'phone' => 'array'
    ];
}
