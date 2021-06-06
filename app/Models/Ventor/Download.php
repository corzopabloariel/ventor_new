<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;

class Download extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order',
        'type',
        'name',
        'image',
        'files'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'image' => 'array',
        'files' => 'array'
    ];

    /* ================== */
    public static function type(String $type)
    {
        return self::where("type", $type);
    }

    public static function gets()
    {
        $elements = self::orderBy("type")->orderBy("order")->get();
        $value = collect($elements)->map(function($item) {
            $img = $files = $name = null;
            $name = $item->name;
            if (isset($item->image["i"]))
                $img = $item->image["i"];
            if (!empty($item->files)) {
                $files = collect($item->files)->map(function($x) use ($item) {
                    $file = (isset($x["file"]["i"]) && \Auth::guard('web')->check()) || $item->type == "PUBL" ? $x["file"]["i"] : null;
                    $nameExt = $x["file"]["n"];
                    if (!str_contains($nameExt, '.'))
                        $nameExt .= ".{$x["file"]["e"]}";
                    return ["name" => $x["file"]["n"], "nameExt" => $nameExt, "file" => $file, "order" => $item["order"]];
                })->sortBy('order')->toArray();//->whereNotNull('file')
            }
            return ["id" => $item["id"], "image" => $img, "name" => $name, "files" => $files, "type" => $item->type];
        })->toArray();
        $grouped = collect($value)->groupBy(function ($item, $key) {
            return $item['type'];
        })->toArray();
        return $grouped;
    }


    public static function order(Request $request) {

        collect($request->ids)->map(function ($ids, $type) {

            collect($ids)->map(function ($download_id, $key) {

                $download = self::find($download_id);
                Ticket::add(3, $download->id, 'downloads', 'Se modificó el valor', [$download->order, $key, 'order']);
                $download->fill(["order" => $key]);
                $download->save();

            });

        });

        return responseReturn(false, 'Orden guardado');

    }
}
