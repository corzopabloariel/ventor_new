<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = "brands";
    protected $fillable = [
        "name",
        "slug"
    ];
    public static function removeAll() {

        try {
            self::truncate();
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }
}
