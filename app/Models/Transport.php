<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\TransportResource;

class Transport extends Model {

    protected $fillable = array(
        'code',
        'description',
        'address',
        'phone',
        'person'
    );
    public static function gets($request) {

        $transports = self::where('id', '!=', '')->get();
        $transportsResource = TransportResource::collection(
            $transports
        );
        return
        array(
            'error'     => false,
            'status'    => 202,
            'message'   => 'OK',
            'elements'  => $transportsResource
        );

    }
    /* ================== */
    public static function create($attr) {

        $model = self::where('code', $attr['code'])->first();
        if (!$model) {

            $model = new self;
            $model->code = $attr['code'];

        }
        if (isset($attr['description'])) {

            $model->description = $attr['description'];

        }
        if (isset($attr['address'])) {

            $model->address = $attr['address'];

        }
        if (isset($attr['phone'])) {

            $model->phone = $attr['phone'];

        }
        if (isset($attr['person'])) {

            $model->person = $attr['person'];

        }
        $model->save();
        return $model;

    }
    public static function updateCollection(Bool $fromCron = false) {

        set_time_limit(0);
        $model = new self;
        $properties = $model->getFillable();
        $errors = [];
        $source = implode('/', [configs("FOLDER"), config('app.files.folder'), configs("FILE_TRANSPORT", config('app.files.transports'))]);
        if (file_exists($source)) {

            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Responsable') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) {

                    continue;

                }
                $data = array_combine($properties, $elements);
                self::create($data);

            }
            fclose($file);
            if ($fromCron) {

                return responseReturn(true, 'Transportes insertados: '.self::count().' / Errores: '.count($errors));

            }
            return responseReturn(false, 'Transportes insertados: '.self::count().' / Errores: '.count($errors));

        }
        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }
        return responseReturn(true, 'Archivo no encontrado', 1, 400);

    }

}
