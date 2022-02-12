<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'type',
        'send_by',
        'from',
        'to',
        'subject',
        'body',
        'sent',
        'error',
        'use',
        'is_order',
        'ip',
        'user_agent'
    ];
    protected $casts = [
        'to'        => 'array',
        'sent'      => 'boolean',
        'error'     => 'boolean',
        'is_order'  => 'boolean'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

}
