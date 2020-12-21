<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ventor';
    protected $fillable = [
        'address',
        'captcha',
        'phone',
        'email',
        'social',
        'metadata',
        'images',
        'section',
        'miscellaneous',
        'form'
    ];
    protected $dates = [];

    protected $casts = [
        'address' => 'array',
        'captcha' => 'array',
        'phone' => 'array',
        'email' => 'array',
        'social' => 'array',
        'metadata' => 'array',
        'images' => 'array',
        'section' => 'array',
        'miscellaneous' => 'array',
        'form' => 'array'
    ];
}
