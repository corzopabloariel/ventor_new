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
        'sent',
        'error',
        'from',
        'ip',
        'user_agent'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];


    /* ================== */
    public static function create($attr)
    {
        $mongo = EmailMongo::create($attr);
        $model = new self;
        $model->type = 'SMTP';
        $model->uid = $mongo->_id;
        $model->use = $attr['use'];
        $model->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $model->ip = self::getIp();
        $model->save();

        return $model;
    }

    private static function getIp() {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
