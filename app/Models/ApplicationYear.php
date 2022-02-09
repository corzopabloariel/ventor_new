<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationYear extends Model
{
    use HasFactory;
    protected $table = 'application_year';
    protected $fillable = [
        'brand_id',
        'model_id',
        'name',
        'slug'
    ];
    public function products() {

        return $this->hasMany(ApplicationProduct::class, 'year_id', 'id')
            ->where('model_id', $this->model_id)
            ->where('brand_id', $this->brand_id);

    }
}
