<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Traits\ModelTrait;

class Transport extends Model
{
    use ModelTrait;

    protected $fillable = [
        'code',
        'description',
        'address',
        'phone',
        'person'
    ];

    /* ================== */
    public static function removeAll() {

        try {

            self::truncate();
            return true;

        } catch (\Throwable $th) {

            return false;

        }

    }

    /* ================== */
    public static function getAll(String $attr = "_id", String $order = "ASC") {

        return self::orderBy($attr, $order)->get();

    }

    public static function gets(String $_id = "") {

        $elements = self::getAll();
        $client = empty($_id) ? null : Client::one($_id);
        $options = collect($elements)->map(function($item) use ($client) {
            $selected = "";
            $attr = isset($client->transportista["code"]) ? "code" : "cod";
            if (!empty($client) && $client->transportista[$attr] == $item->code)
                $selected = "selected";
            return "<option {$selected} value='{$item->code}'>{$item->description}</option>";
        })->join("");
        return $options;

    }

    public static function one(String $_id, String $attr = "_id") {

        return self::where($attr, $_id)->first();

    }

    /* ================== */
    public static function create($attr) {

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


    public static function updateCollection(Bool $fromCron = false) {

        set_time_limit(0);
        $model = new self;
        $properties = $model->getFillable();
        $errors = [];
        $source = implode('/', [env('ROUTE_FILE'), config('app.files.folder'), configs("FILE_TRANSPORT", config('app.files.transports'))]);
        if (file_exists($source)) {

            self::removeAll();
            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Responsable') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) continue;
                try {

                    $data = array_combine($properties, $elements);
                    self::create($data);

                } catch (\Throwable $th) {

                    $errors[] = $elements;

                }

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

    public function getName() {
        return 'transports';
    }
}
