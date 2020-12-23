<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $fillable = [
        'section',
        'data'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /* ================== */
    public static function section(String $section)
    {
        return self::where("section", $section)->first();
    }
}
