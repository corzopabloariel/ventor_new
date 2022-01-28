<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "products_brand";
    protected $fillable = [
        "product_id",
        "brand_id"
    ];
}
