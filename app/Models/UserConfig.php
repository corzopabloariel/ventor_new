<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    use HasFactory;

    protected $table = 'config_user';

    protected $fillable = [
        'username',
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

    public static function create($attr) {

        $flagNew = false;
        $model = self::where('username', $attr['username'])->first();
        if (!$model) {
            $flagNew = true;
            $model = new self;
            $model->username = $attr['username'];
        }
        if (isset($attr['active_url']))
            $model->active_url = $attr['active_url'];
        if (isset($attr['url']))
            $model->url = $attr['url'];
        if (isset($attr['dark_mode']))
            $model->dark_mode = $attr['dark_mode'];
        if (isset($attr['active_favorite']))
            $model->active_favorite = $attr['active_favorite'];
        $model->paginate = isset($attr['paginate']) ? $attr['paginate'] : configs("PAGINADO");
        if ($flagNew) {
            $model->other = isset($attr['other']) ? $attr['other'] : ['cart' => 1];
        } else {
            $otherDB = empty($model->other) ? ['cart' => 1] : $model->other;
            $other = isset($attr['other']) ? $attr['other'] : ['cart' => 1];
            $model->other = array_merge(
                json_decode(json_encode($otherDB), true),
                json_decode(json_encode($other), true)
            );
        }
        $model->save();
        return $model;

    }
}
