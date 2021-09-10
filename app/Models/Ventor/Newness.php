<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;

class Newness extends Model
{
    use SoftDeletes;

    protected $table = "news";

    protected $fillable = [
        'order',
        'file',
        'image',
        'name',
    ];

    protected $casts = [
        'image' => 'array',
        'file' => 'array'
    ];

    public function getName() {
        return 'news';
    }

    public static function gets($limit)
    {
        if (!empty($limit))
            $elements = self::orderBy("order")->limit($limit)->get();
        else
            $elements = self::orderBy("order")->get();
        $value = collect($elements)->map(function($x) {
            $img = $file = $name = null;
            $name = $x->name;
            if (isset($x->image["i"]))
                $img = $x->image["i"];
            if (isset($x->file["i"]) && \Auth::guard('web')->check())
                $file = $x->file["i"];
            return ["image" => $img, "name" => $name, "file" => $file];
        })->toArray();
        return $value;
    }


    public static function order(Request $request) {

        collect($request->ids)->map(function ($new_id, $key) {

            $new = self::find($new_id);
            Ticket::add(3, $new->id, 'news', 'Se modificó el valor', [$new->order, $key, 'order']);
            $new->fill(["order" => $key]);
            $new->save();

        });

        return responseReturn(false, 'Orden guardado');

    }
}
