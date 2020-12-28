<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Client;

class Transport extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'transports';
    protected $primaryKey = '_id';
    protected $fillable = [
        'code',
        'description',
        'address',
        'phone',
        'person'
    ];

    /* ================== */
    public static function removeAll()
    {
        try {
            self::truncate();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /* ================== */
    public static function getAll(String $attr = "_id", String $order = "ASC")
    {
        return self::orderBy($attr, $order)->get();
    }

    public static function gets(String $_id = "")
    {
        $elements = self::getAll();
        $client = empty($_id) ? null : Client::one($_id);
        $options = collect($elements)->map(function($item) use ($client) {
            $selected = "";
            if (!empty($client) && $client->transportista["code"] == $item->code)
                $selected = "selected";
            return "<option {$selected} value='{$item->code}'>{$item->description}</option>";
        })->join("");
        return $options;
    }

    public static function one(String $_id, String $attr = "_id")
    {
        return self::where($attr, $_id)->first();
    }

    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        if (isset($attr['code']))
            $model->code = $attr['code'];
        if (isset($attr['description']))
            $model->description = $attr['description'];
        if (isset($attr['address']))
            $model->address = $attr['address'];
        if (isset($attr['phone']))
            $model->phone = $attr['phone'];
        if (isset($attr['person']))
            $model->person = $attr['person'];
        $model->save();

        return $model;
    }
}
