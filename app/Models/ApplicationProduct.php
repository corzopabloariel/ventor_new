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
        'product_id',
        'brand_id',
        'model_id',
        'year_id',
        'type'
    ];
    public function product() {

        return $this->belongsTo(Product::class, 'product_id', 'id');

    }
    public function application() {

        return $this->belongsTo(ApplicationTmp::class, 'application_id', 'id');

    }
}
