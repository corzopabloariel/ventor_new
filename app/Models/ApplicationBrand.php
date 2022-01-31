<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationBrand extends Model
{
    use HasFactory;
    protected $table = 'application_brand';
    protected $fillable = [
        'name',
        'slug'
    ];
}
