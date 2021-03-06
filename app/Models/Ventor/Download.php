<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\DownloadUser;

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

    public function getName() {
        return 'downloads';
    }

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
        $files = [
            ['name' => 'VENTOR LISTA DE PRECIOS FORMATO TXT', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO TXT.txt', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt' : null],
            ['name' => 'VENTOR LISTA DE PRECIOS FORMATO DBF', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO DBF.dbf', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf' : null],
            ['name' => 'VENTOR LISTA DE PRECIOS FORMATO XLS', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO XLS.xls', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls' : null]
        ];
        if (file_exists(public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt') && configs('SHOW_GENERAL', env('SHOW_GENERAL')) == "true") {
            if (!isset($grouped['PREC'])) {
                $grouped['PREC'] = array();
            }
            array_unshift($grouped['PREC'],
                array(
                    'id' => 0,
                    'image' => 'static/lista_precios_general.jpg',
                    'name' => 'LISTA DE PRECIOS GENERAL',
                    'files' => $files,
                    'type' => 'PREC'
                )
            );
        }

        return $grouped;
    }


    public function track() {
        if (\Auth::check()) {
            $flag = true;
            $dateStart = date("Y-m-d H:i:s", strtotime("-1 hour"));
            $dateEnd = date("Y-m-d H:i:s");
            $user = \Auth::user();
            if ($user->limit != 0) {
                if ($user->downloads->count() != 0) {
                    if ($user->limit <= $user->downloads->whereBetween("created_at", [$dateStart, $dateEnd])->count()) {
                        return response()->json([
                            "error" => 1,
                            "msg" => 'Llego al límite de descargas por hora'
                        ], 200);
                    }
                }
                DownloadUser::create(["download_id" => $this->id, "user_id" => $user->id]);
            }
            return response()->json([
                "error" => 0,
                "success" => true
            ], 200);
        }
        return response()->json([
            "error" => 1,
            "msg" => 'Ingrese a su cuenta para poder acceder a los archivos'
        ], 200);
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
