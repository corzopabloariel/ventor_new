<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $table = "parts";
    protected $fillable = [
        'name',
        'family_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function subparts()
    {
        return $this->hasMany('App\Models\Subpart','part_id','id')->get();
    }
}
