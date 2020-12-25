<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Order extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $primaryKey = '_id';
    protected $fillable = [
        'client_id',
        'client_uid',
        'seller',
        'products',
        'obs'
    ];

}
