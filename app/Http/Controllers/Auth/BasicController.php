<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Image;
class BasicController extends Controller
{
    public $acceptedFormats = [ 'gif' , 'png' ,'jpg', 'jpeg' , 'pdf' , 'bmp' , 'svg' , 'txt' , 'xls' , 'dbf' ];
    public $model;
    public function __construct() {
        App::setLocale("es");
        //$this->model = new Image;
    }

    public function update() {
        $data = [
            "view" => "auth.parts.update",
            "title" => "Actualizar DB"
        ];
        return view('auth.distribuidor',compact('data'));
    }

    public function imagen( Request $request ) {
        set_time_limit(0);
        $dataRequest = $request->all();
        if( empty( $dataRequest ) ) {
            $data = [
                "view"      => "auth.parts.imagen",
                "title"     => "Imágenes",
                "elementos"  => Image::get(),
                "buttons" => [
                    [ "i" => "fas fa-trash-alt" , "b" => "btn-danger" , "t" => "Eliminar" ]
                ],
            ];
            return view('auth.distribuidor',compact('data'));
        }
    }
    public function imagenStore(Request $request, $data = null) {
        return self::store($request, $data, $this->model);
    }
    public function imagenUpdate(Request $request, \App\Imagen $element) {
        return self::imagenStore($request, $element);
    }
    public function imagenShow(Request $request) {
        return $this->model->find($request->id);
    }
    public function imagenDestroy(Request $request) {
        return self::delete($this->model->find($request->id), $this->model->getFillable());
    }
    public function deleteFile(Request $request) {
        try {
            if (empty($request->id)) {
                $aux = [];
                $data = DB::table($request->entidad)
                    ->where('id', $request->idPadre)->first();
                $data = collect($data)->map(function($x){ return (array) $x; })->toArray()[$request->column][0];
                $data = json_decode($data, true);
                $filename = $data[$request->attr];
                unset($data[$request->attr]);
                $aux[$request->column] = json_encode($data);
                DB::table($request->entidad)
                    ->where('id', $request->idPadre)
                    ->update($aux);
            } else {
                $data = [];
                $data[$request->attr] = NULL;
                DB::table($request->entidad)
                    ->where('id', $request->id)
                    ->update($data);
            }
            $filename = public_path() . "/{$request->file}";
            if (file_exists($filename))
                unlink($filename);
        } catch (\Throwable $th) {
            return json_encode(["error" => 1, "msg" => "Ocurrió un error en el borrado del archivo"]);
        }
        return json_encode(['success' => true, "error" => 0, "msg" => "Archivo eliminado correctamente"]);
    }

    public function count(Request $request) {
        try {
            $entidad = $request->table;
            eval("\$model = new \\App\\{$entidad};");
            $data = $model;
            if (isset($request->id)) {
                $attr = $request->attr;
                $data = $data->where($attr, $request->id);
            }
            $fillable = $model->getFillable();
            if (in_array("elim", $fillable))
                $data = $data->where("elim", 0);
            $data = $data->count();
        } catch (\Throwable $th) {
            return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
        }
        return json_encode(['success' => true, "error" => 0, "data" => $data]);
    }

    public function relation(Request $request) {
        try {
            $entidad = $request->table;
            eval("\$model = new \\App\\{$entidad};");
            $attr = json_decode($request->attr, true);
            if (isset($request->id))
                $data = $model->find($request->id, $attr);
            else {
                $fillable = $model->getFillable();
                $data = $model;
                if (in_array("elim", $fillable))
                    $data = $data->where("elim", 0);
                $data = $data->get($attr)->toArray();
            }
        } catch (\Throwable $th) {
            return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
        }
        return json_encode(['success' => true, "error" => 0, "data" => $data]);
    }
    public function join(Request $request) {
        try {
            $entities = json_decode($request->entities, true);
            $table = array_shift($entities);
            $attr = json_decode($request->attr, true);
            $join = json_decode($request->join, true);
            $id = $request->id;
            $data = DB::table($table);
            for ($i = 0; $i < count($entities); $i++) {
                $aux = explode(",", $join[$i]);
                $data = $data->join($entities[$i], $aux[0], $aux[1], $aux[2]);
            }
            $data = $data->where("{$table}.id", $id);
            $data = $data->get([$attr[0], DB::raw($attr[1])])->first();
        } catch (\Throwable $th) {
            return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
        }
        return json_encode(['success' => true, "error" => 0, "data" => $data]);
    }

    public function delete($data, $fillable, $total = 0) {
        DB::beginTransaction();
        try {
            if (in_array("image", $fillable)) {
                if(!empty($data->image)) {
                    $filename = public_path() . "/{$data->image['i']}";
                    if (file_exists($filename))
                        unlink($filename);
                }
            }
            if (in_array("file", $fillable)) {
                if (!empty($data->file)) {
                    $filename = public_path() . "/{$data->file['i']}";
                    if (file_exists($filename))
                        unlink($filename);
                }
            }
            if (in_array("photo", $fillable)) {
                if (!empty($data->photo)) {
                    $filename = public_path() . "/{$data->photo['i']}";
                    if (file_exists($filename))
                        unlink($filename);
                }
            }
            if ($total)
                $data->forceDelete();
            else
                $data->delete();
        } catch (\Throwable $th) {
            DB::rollback();
            return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
        }
        DB::commit();
        return json_encode(['success' => true, "error" => 0]);
    }
    /**
     * Función encargada de construir los objetos a guardar
     *
     * Mejora en el merge de elementos múltiples
     * @version 2.0.0
     * @param @type object request $request
     * @param @type object $data
     * @param @type array $merge
     * @date 19/02/2020
     */
    public function TP_STRING($attr, $value, $valueNew, $specification, $index = 0) {
        return self::clear($valueNew);
    }
    public function TP_STRING_value(...$value) {
        return $value[0];
    }
    public function TP_EMAIL($attr, $value, $valueNew, $specification, $index = 0) {
        return trim($valueNew);
    }
    public function TP_EMAIL_value(...$value) {
        return $value[0];
    }
    public function TP_PASSWORD($attr, $value, $valueNew, $specification, $index = 0) {
        return empty($valueNew) ? $value : Hash::make($valueNew);
    }
    public function TP_PASSWORD_value(...$value) {
        return $value[0];
    }
    public function TP_RELATIONSHIP($attr, $value, $valueNew, $specification, $index = 0) {
        return empty($valueNew) ? null : $valueNew;
    }
    public function TP_RELATIONSHIP_value(...$value) {
        return $value[0];
    }
    public function TP_ENUM($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_ENUM_value(...$value) {
        return $value[0];
    }
    public function TP_FECHA($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_FECHA_value(...$value) {
        return $value[0];
    }
    public function TP_LINK($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_LINK_value(...$value) {
        return $value[0];
    }
    public function TP_TEXT($attr, $value, $valueNew, $specification, $index = 0) {
        return self::clear($valueNew);
    }
    public function TP_TEXT_value(...$value) {
        return $value[0];
    }
    public function TP_ENTERO($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_ENTERO_value(...$value) {
        return $value[0];
    }
    public function TP_LIST($attr, $value, $valueNew, $specification, $index = 0) {
        return self::clear($valueNew);
    }
    public function TP_LIST_value(...$value) {
        return $value[0];
    }
    public function TP_PHONE($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_PHONE_value(...$value) {
        return $value[0];
    }
    public function TP_CHECK($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_CHECK_value(...$value) {
        return $value[0];
    }
    public function TP_COLOR($attr, $value, $valueNew, $specification, $index = 0) {
        return $valueNew;
    }
    public function TP_COLOR_value(...$value) {
        return $value[0];
    }
    public function TP_SLUG($attr, $value, $valueNew, $specification, $index = 0) {
        return Str::slug($valueNew, "-");
    }
    public function TP_SLUG_value(...$value) {
        return Str::slug($value, "-");
    }
    public function TP_IMAGE($attr, $value, $valueNew, $specification, $index = 0) {
        $file = null;
        $file = isset($valueNew[$attr]) ? $valueNew[$attr] : null;
        $path = "images/";
        $old = empty($value) ? null : $value["i"];//ruta vieja;
        $fileName = empty($value) ? null : $value["n"];//mantiene el nombre, reemplaza archivo
        if (isset($specification["FOLDER"]))
            $path .= "{$specification["FOLDER"]}/";
        if (!file_exists($path))
            mkdir($path, 0777, true);
        if (!empty($file)) {
            $fileNameNew = $file->getClientOriginalName();
            list($aux, $ext) = explode(".", $fileNameNew);
            $fileNameNew = $aux;
            if (empty($valueNew["check"])) {
                if (empty($fileName))
                    $fileNameNew = time() . "_{$attr}_{$index}";
                else
                    $fileNameNew = $fileName;
            }
            if (!empty($old)) {
                if (file_exists($old))
                    unlink($old);
            }
            $file->move($path, "{$fileNameNew}.{$ext}");
            return [
                "i" => "{$path}{$fileNameNew}.{$ext}",
                "e" => $ext,
                "n" => $fileNameNew,
                "d" => getimagesize("{$path}{$fileNameNew}.{$ext}")
            ];
        }
        return $value;
    }
    public function TP_IMAGE_value(...$value) {
        return isset($value[0][$value[1]]) ? $value[0][$value[1]] : null;
    }
    public function object($request, $data = null) {
        $datosRequest = $request->all();
        if( isset( $datosRequest["REMOVE"] ) ) {
            $datosRequest["REMOVE"] = json_decode( $datosRequest["REMOVE"] , true );
            for( $i = 0 ; $i < count( $datosRequest["REMOVE"] ) ; $i ++ ) {
                $filename = $datosRequest[ "REMOVE" ][ $i ];
                if ( file_exists( $filename ) )
                    unlink( $filename );
            }
        }
        if (!empty($data)) {
            try {
                $data = $data->toArray();
            } catch (\Throwable $th) {}
        }
        $datosRequest["ATRIBUTOS"] = json_decode($datosRequest["ATRIBUTOS"], true);
        $OBJ = [];
        for ($x = 0 ; $x < count($datosRequest["ATRIBUTOS"]); $x++) {
            $aux = $datosRequest["ATRIBUTOS"][$x];
            if (isset($aux["TIPO"])) {
                switch ($aux["TIPO"]) {
                    case "U":
                        $attrs = array_keys($aux["DATA"]["especificacion"]);
                        $specifications = $aux["DATA"]["especificacion"];
                        $details = $aux["DATA"]["detalles"];
                        $values = $datosRequest[$aux["DATA"]["name"]];
                        $column = isset($aux["COLUMN"]) ? $aux["COLUMN"] : NULL;
                        for($i = 0; $i < count($attrs); $i++) {
                            $attr = $attrs[$i];
                            $specification = $specifications[$attr];
                            if ($specification == "TP_ARRAY" || $specification == "TP_DELETE")
                                continue;
                            $detail = isset($details[$attr]) ? $details[$attr] : null;
                            if ($specification == "TP_SLUG")
                                $valueNew = $values[str_replace("_slug", "", $attr)];
                            else
                                $valueNew = isset($values[$attr]) ? $values[$attr] : null;
                            if (empty($column)) {
                                $value = isset($data[$attr]) ? $data[$attr] : null;
                                $OBJ[$attr] = call_user_func_array("self::{$specification}", [$attr, $value, $valueNew, $detail]);
                            } else {
                                $value = isset($data[$column][$attr]) ? $data[$column][$attr] : null;
                                if (!isset($OBJ[$column]))
                                    $OBJ[$column] = [];
                                $OBJ[$column][$attr] = call_user_func_array("self::{$specification}", [$attr, $value, $valueNew, $detail]);
                            }
                        }
                    break;
                    case "M":
                        $attrs = array_keys($aux["DATA"]["especificacion"]);
                        $values = $datosRequest[$aux["DATA"]["name"]];
                        $column = isset($aux["COLUMN"]) ? $aux["COLUMN"] : NULL;
                        $specifications = $aux["DATA"]["especificacion"];
                        $details = $aux["DATA"]["detalles"];
                        $OBJ[$column] = [];
                        for ($i = 0; $i < count($attrs); $i++) {
                            $value = isset($data[$column]) ? $data[$column] : null;
                            $attr = $attrs[$i];
                            $specification = $specifications[$attr];
                            if ($specification == "TP_ARRAY" || $specification == "TP_DELETE")
                                continue;
                            $detail = isset($details[$attr]) ? $details[$attr] : null;
                            if ($specification == "TP_SLUG")
                                $valueNew = $values[str_replace("_slug", "", $attr)][$column];
                            else
                                $valueNew = isset($values[$attr][$column]) ? $values[$attr][$column] : null;
                            for ($j = 0; $j < count($valueNew); $j++) {
                                $valueAux = isset($value[$j][$attr]) ? $value[$j][$attr] : null;
                                $OBJ[$column][$j][$attr] = call_user_func_array("self::{$specification}", [$attr, $valueAux, $valueNew[$j], $detail, $j]);
                            }
                        }
                        if (!empty($aux["DATA"]["sorteable"])) {
                            for($i = 0; $i < count($OBJ[$column]) - 1 ; $i ++) {
                                for($j = $i + 1; $j < count($OBJ[$column]) ; $j ++) {
                                    if ($OBJ[$column][ $i ][$aux["DATA"]["sorteable"]] > $OBJ[$column][$j][$aux["DATA"]["sorteable"]]) {
                                        $temp = $OBJ[$column][$i];
                                        $OBJ[$column][$i] = $OBJ[$column][$j];
                                        $OBJ[$column][$j] = $temp;
                                    }
                                }
                            }
                        }
                        break;
                    case "A":
                        $values = $datosRequest[$aux["DATA"]["name"]];
                        $column = isset($aux["COLUMN"]) ? $aux["COLUMN"] : NULL;
                        $OBJ[$column] = [];
                        foreach($aux["DATA"]["especificacion"] AS $specification => $type) {
                            $OBJ[$column] = $values[$specification][$column];
                        }
                    break;
                }
            } else {
                if (isset($aux["EMPTY"]));
                    $OBJ[$aux["EMPTY"]] = null;
            }
        }
        return $OBJ;
    }

    public function store($request, $data, $model, $rule = null, $return = false, $default = []) {
        $aux = $request->all();
        $attr = json_decode($request->ATRIBUTOS, true);
        $flag = false;
        $aa = [];
        for($i = 0; $i < count($attr); $i++) {
            if (!isset($attr[$i]["DATA"]))
                continue;
            $elements = [];
            $values = [];
            $table = $attr[$i]["DATA"]["name"];
            $element = $attr[$i];
            switch ($element["TIPO"]) {
                case "U":
                    foreach($element["DATA"]["especificacion"] AS $specification => $type) {
                        if ($type != "TP_ARRAY") {
                            if (isset($aux[$table][$specification])) {
                                $value = $aux[$table][$specification];
                                $values[$specification] = call_user_func_array("self::{$type}_value", [$value, $specification]);
                            }
                        }
                    }
                break;
                case "A":
                case "M":
                    foreach($element["DATA"]["especificacion"] AS $specification => $type) {
                        if ($type != "TP_ARRAY") {
                            if (isset($aux[$table][$specification][$element["COLUMN"]])) {
                                $value = $aux[$table][$specification][$element["COLUMN"]];
                                $values[$specification] = call_user_func_array("self::{$type}_value", [$value, $specification]);
                            }
                        }
                    }
                break;
            }
            $rules = isset($attr[$i]["DATA"]["rules"]) ? $attr[$i]["DATA"]["rules"] : [];
            if (!empty($rules)) {
                foreach($values AS $k => $v) {
                    if (isset($rules[$k]))
                        $elements[$k] = $v;
                }
            }
            $aa[] = ["r" => $rules, "e" => $elements, "t" => $element["TIPO"]];
            $normal = true;
            foreach($rules AS $k => $v) {
                if (isset($elements[$k])) {
                    if (is_array($elements[$k]))
                        $normal = false;
                }
            }
            if ($normal) {
                $validator = Validator::make($elements, $rules);
                if ($validator->fails())
                    $flag = true;
            } else {
                foreach($rules AS $k => $v) {
                    $aux_r = [];
                    if (isset($elements[$k])) {
                        for($j = 0; $j < count($elements[$k]); $j++) {
                            $aux_r[$k] = $elements[$k][$j];
                            $validator = Validator::make($aux_r, [$k => $v]);
                            if ($validator->fails())
                                $flag = true;
                        }
                    }
                }
            }
        }
        if ($flag)
            return json_encode(["error" => 1, "msg" => "Error en los datos de ingreso."]);
        else {
            DB::beginTransaction();
            try {
                $OBJ = self::object($request, $data);
                if ($rule) {
                    $flag = true;
                    foreach ($rule["DATA"] AS $r) {
                        if (isset($OBJ[$r["k"]])) {
                            if ($OBJ[$r["k"]] != $r["v"])
                                $flag = false;
                        }
                    }
                    if ($flag) {
                        foreach ($rule["CHANGE"] AS $r)
                            $OBJ[$r["k"]] = $r["v"];
                    }
                }
                if (is_numeric($OBJ))
                    return json_encode(["error" => 1, "msg" => "Extensión no válida"]);
                if (!empty($default)) {
                    foreach ($default AS $k => $v) {
                        if (isset($OBJ[$k]))
                            $OBJ[$k] = $v;
                    }
                }
                if ($return) {
                    DB::commit();
                    return json_encode(["success" => true, "error" => 0, "data" => $OBJ]);
                }
                if(is_null($data))
                    $data = $model->create($OBJ);
                else {
                    $data->fill($OBJ);
                    $data->save();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
            }
            DB::commit();
            return json_encode(["success" => true, "error" => 0, "data" => $data]);
        }
    }

    public function edit (Request $request) {
        DB::beginTransaction();
        try {
            if (isset($request->ATRIBUTOS)) {
                $OBJ = [];
                $data = DB::table($request->table)->find($request->id, [$request->key]);
                $value = collect($data)->map(function($x){ return (array) $x; })->toArray()[$request->key][0];
                $dataRequest = json_decode($request->ATRIBUTOS, true)[0];
                $attr = $request->key;
                $specification = $dataRequest["DATA"]["especificacion"][$attr];
                $valueNew = $request->all()[$dataRequest["DATA"]["name"]][$attr];
                $detail = isset($dataRequest["DATA"]["detalles"][$attr]) ? $dataRequest["DATA"]["detalles"][$attr] : null;
                $aux = call_user_func_array("self::{$specification}", [$attr, $value, $valueNew, $detail]);
                if (is_string($aux))
                    $OBJ[$attr] = $aux;
                else
                    $OBJ[$attr] = json_encode($aux);
                $db = DB::table($request->table)
                    ->where('id', $request->id)
                    ->update($OBJ);
                DB::commit();
                return json_encode(['success' => true, "error" => 0, "obj" => DB::table($request->table)->find($request->id)]);
            } else {
                $data = [];
                $data[$request->key] = $request->value;
                DB::table($request->table)
                    ->where('id', $request->id)
                    ->update($data);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return json_encode(["error" => 1, "msg" => $th->errorInfo[2]]);
        }
        DB::commit();
        return json_encode(['success' => true, "error" => 0]);
    }

    public function clear ($text) {
        return str_replace(["&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;", URL::to("/")], ["á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ", ""], $text);
    }
}