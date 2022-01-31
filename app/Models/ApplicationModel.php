<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationModel extends Model
{
    use HasFactory;
    protected $table = 'application_model';
    protected $fillable = [
        'brand_id',
        'name',
        'slug'
    ];
}
