<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadUser extends Model
{
    use HasFactory;
    protected $table = "download_user";
    protected $fillable = [
        "user_id",
        "downlaod_id"
    ];
}
