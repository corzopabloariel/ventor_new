<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = "cart";
    protected $fillable = [
        'uid',
        'data',
        'user_id',
    ];
    protected $casts = [
        'data' => 'array'
    ];
    /* ================== */
    public static function create($attr)
    {
        $model = self::where("user_id", isset($attr["user_id"]) ? $attr["user_id"] : \auth()->guard('web')->user()->id)->whereNull("uid")->first();
        if (!$model)
            $model = new self;
        $model->data = $attr['data'];
        $model->user_id = isset($attr["user_id"]) ? $attr["user_id"] : \auth()->guard('web')->user()->id;
        $model->save();
        return $model;
    }
    public static function last($user = null)
    {
        if (empty($user))
            return self::where("user_id", \auth()->guard('web')->user()->id)->whereNull("uid")->first();
        return self::where("user_id", $user->id)->whereNull("uid")->first();
    }
}
