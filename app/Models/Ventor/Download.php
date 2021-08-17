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
            if (isset($item->image["i"]) && file_exists(public_path().'/'.$item->image["i"])) {
                $type = pathinfo(public_path().'/'.$item->image["i"], PATHINFO_EXTENSION);
                $img = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(public_path().'/'.$item->image["i"]));
            }
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
            ['name' => 'FORMATO TXT', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO TXT.txt', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt' : null],
            ['name' => 'FORMATO DBF', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO DBF.dbf', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf' : null],
            ['name' => 'FORMATO XLS', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO XLS.xls', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls' : null],
            ['name' => 'FORMATO CSV', 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO CSV.csv', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv' : null]
        ];
        if (file_exists(public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt') && configs('SHOW_GENERAL', env('SHOW_GENERAL')) == "true") {
            if (!isset($grouped['PREC'])) {
                $grouped['PREC'] = array();
            }
            $type = pathinfo(config('app.static').'img/lista_precios_general.jpg', PATHINFO_EXTENSION);
            array_unshift($grouped['PREC'],
                array(
                    'id' => 0,
                    'image' => 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(config('app.static').'img/lista_precios_general.jpg')),
                    'name' => 'LISTA DE PRECIOS GENERAL',
                    'files' => $files,
                    'type' => 'PREC',
                    'separate' => true
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
