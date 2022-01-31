<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationProduct extends Model
{
    use HasFactory;
    protected $table = 'application_products';
    protected $fillable = [
        'application_id',
        'brand_id',
        'model_id',
        'year_id',
        'product_id',
        'type'
    ];
}
