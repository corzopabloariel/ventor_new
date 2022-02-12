<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductApplication extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "products_application";
    protected $fillable = [
        "product_id",
        "application_id"
    ];
}
