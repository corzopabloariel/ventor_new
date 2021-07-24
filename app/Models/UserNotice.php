<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_notice';

    protected $fillable = [
        'username',
        'data',
        'read'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'bool'
    ];
}
