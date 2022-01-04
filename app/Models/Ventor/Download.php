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
    public static $PRICES = array(
        'file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt',
        'file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf',
        'file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls',
        'file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv'
    );
    public static $TYPES = array(
        'pdf'   => 'application/pdf',
        'dbf'   => 'application/dbf',
        'csv'   => 'text/csv',
        'txt'   => 'text/plain',
        'xls'   => 'data:application/vnd.ms-excel;base64'
    );
    public static $CATEGORIES = array(
        'PUBL' => 'Descargas e instructivos',
        'CATA' => 'Catálogo',
        'PREC' => 'Listas de precios',
        'OTRA' => 'Otra'
    );
    public function getElementsAttribute() {

        if (empty($this->files)) {

            return array();

        }
        $download = $this;
        $types = self::$TYPES;
        $files = collect($this->files)->map(function($x) use ($download, $types) {

            $file = (isset($x['file']['i']) && \Auth::guard('web')->check()) || $download->type == 'PUBL' ? $x['file']['i'] : null;
            $nameExt = $x['file']['n'];
            if (!str_contains($nameExt, '.'))
                $nameExt .= ".{$x['file']['e']}";
            return array(
                'name'      => $x['file']['n'],
                'nameExt'   => $nameExt,
                'type'      => $types[$x['file']['e']] ?? null,
                'file'      => $file,
                'order'     => $download['order']
            );

        })->sortBy('order')->toArray();
        return array_values($files);

    }
    /* ================== */
    public static function type(String $type)
    {
        return self::where("type", $type);
    }

    public static function gets($order = array()) {

        $categories = self::$CATEGORIES;
        $types = self::$TYPES;
        $elements = self::orderBy("type")->orderBy("order")->get();
        $elements = $elements->map(function($item) {

            $img = $files = $name = null;
            $name = $item->name;
            if (isset($item->image['i']) && file_exists(public_path().'/'.$item->image['i'])) {

                $type = pathinfo(public_path().'/'.$item->image['i'], PATHINFO_EXTENSION);
                $img = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(public_path().'/'.$item->image['i']));

            }
            $files = $item->elements;
            return array(
                'id' => $item['id'],
                'image' => $img,
                'name' => $name,
                'files' => $files,
                'type' => $item->type
            );

        })->groupBy(function ($item, $key) {

            return $item['type'];

        })->toArray();
        if (file_exists(storage_path().'/app/public/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt') && configs('SHOW_GENERAL', env('SHOW_GENERAL')) == "true") {

            if (!isset($elements['PREC'])) {

                $elements['PREC'] = array();

            }
            $type = pathinfo(config('app.static').'img/lista_precios_general.jpg', PATHINFO_EXTENSION);
            $files = array(
                array('name' => 'FORMATO TXT', 'type' => $types['txt'], 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO TXT.txt', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt' : null),
                array('name' => 'FORMATO DBF', 'type' => $types['dbf'], 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO DBF.dbf', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf' : null),
                array('name' => 'FORMATO XLS', 'type' => $types['xls'], 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO XLS.xls', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls' : null),
                array('name' => 'FORMATO CSV', 'type' => $types['csv'], 'nameExt' => 'VENTOR LISTA DE PRECIOS FORMATO CSV.csv', 'file' => \Auth::guard('web')->check() ? 'file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv' : null)
            );
            array_unshift($elements['PREC'],
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
        $elementsOrder = array_map(function($element) use ($elements, $categories) {

            return array(
                'title' => $categories[$element],
                'items' => $elements[$element]
            );

        }, $order);
        return $elementsOrder;

    }
    public static function limit() {

        $dateStart = date("Y-m-d H:i:s", strtotime("-2 hour"));
        $dateEnd = date("Y-m-d H:i:s");
        $user = \Auth::user();
        if ($user->limit != 0) {

            if ($user->downloads->count() != 0) {

                if ($user->limit <= $user->downloads->whereBetween("created_at", [$dateStart, $dateEnd])->count()) {

                    return response(
                        array(
                            'error'     => true,
                            'status'    => 400,
                            'message'   => 'Llegó al límite de descargas por hora',
                            'elements'  => array()
                        ),
                        400
                    );

                }

            }

        }
        return response(
            array(
                'error'     => true,
                'status'    => 202,
                'message'   => 'OK',
                'elements'  => array()
            ),
            202
        );

    }
    public static function track($id, $index) {

        if (\Auth::check()) {

            $user = \Auth::user();
            $limit = self::limit();
            if ($limit->original['error']) {

                return $limit;

            }
            $file = null;
            if ($id == 0) {

                $files = self::$PRICES;
                $file = $files[$index];

            } else {

                DownloadUser::create(["download_id" => $id, "user_id" => $user->id]);
                $download = self::find($id);
                $item = $download;
                $files = $download->elements;
                $file = $files[$index]['file'];

            }
            if (file_exists(public_path().'/'.$file)) {

                return file_get_contents(public_path().'/'.$file);

            }
            return null;

        }
        return response(
            array(
                'error'     => true,
                'status'    => 401,
                'message'   => 'Ingrese a su cuenta para poder acceder a los archivos',
                'elements'  => array($id, $index)
            ),
            401
        );

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
