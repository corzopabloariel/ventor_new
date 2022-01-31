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
}
