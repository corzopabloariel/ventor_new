<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value'
    ];

    /* ================== */
    public static function create($attr)
    {
        if (self::where("name", $attr['name'])->first())
            return false;
        $text = new self;
        $text->name = $attr['name'];
        $text->value = $attr['value'];

        $text->save();
        return $text;
    }
}
