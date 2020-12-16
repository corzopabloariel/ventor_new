<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'order',
        'text',
        'image',
        'section',
    ];

    protected $casts = [
        'image' => 'array'
    ];

    /* ================== */
    public static function section(String $section)
    {
        return self::where("section", $section);
    }
}
