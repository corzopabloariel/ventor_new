<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "products_model";
    protected $fillable = [
        "product_id",
        "model_id"
    ];
}
