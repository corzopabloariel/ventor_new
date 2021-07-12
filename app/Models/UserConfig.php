<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    use HasFactory;

    protected $table = 'config_user';

    protected $fillable = [
        'user_id',
        'active_url',
        'url',
        'dark_mode',
        'active_favorite',
        'paginate',
        'other'
    ];

    protected $casts = [
        'other' => 'array'
    ];
}
