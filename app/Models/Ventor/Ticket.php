<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'table',
        'table_id',
        'obs',
        'user_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
