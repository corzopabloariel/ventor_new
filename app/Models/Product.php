<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;
use App\Models\Ventor\Api;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Ventor\Ticket;

class Product extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';
    protected $primaryKey = '_id';
    protected $fillable = [
        'stmpdh_art',
        'use',
        'codigo_ima',
        'stmpdh_tex',
        'usr_stmpdh',
        'precio',
        'web_marcas',
        'cod_subparte',
        'subparte',
        'modelo_anio',
        'parte',
        'cantminvta',
        'fecha_ingr',
        'nro_original',
        'stock_mini',
        'liquidacion',
        'n1',
        'n2',
        'n3',
        'n4',
        'n5',
        'max_ventas',
        'active',
        'application'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_ingr'
    ];
    protected $casts = [
        'stmpdh_art' => 'string',
        'active' => 'bool'
    ];
    protected $appends = [
        'images'
    ];

    /* ================== */
    public static function removeAll($withFlag = false)
    {
        try {
            self::truncate();
            // Maneja el mismo ID
            /*if ($withFlag) {
                $ids = self::pluck('_id');
                self::whereIn('_id', $ids->toArray())->update(['active' => false, 'web_marcas' => []]);
            } else {
                self::where('active', false)->delete();
            }*/
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getImagesAttribute() {
        $total = 1;
        for ($i = 1; $i <= 10; $i++) {
            if (file_exists("/var/www/html/public/IMAGEN/{$this->codigo_ima[0]}/{$this->codigo_ima}-{$i}.jpg")) {
                $total ++;
            }
        }
        return $total;
    }
    public function getPartAttribute()
    {
        return Part::where("name", $this->parte)->first();
    }

    public function getSubpartAttribute()
    {
        return Subpart::where("code", $this->subparte["code"])->first();
    }

    /* ================== */
    public static function getAll(String $attr = "_id", String $order = "ASC")
    {
        return self::orderBy($attr, $order)->get();
    }

    public static function one(\Illuminate\Http\Request $request, String $value, String $attr = "_id")
    {
        $value = str_replace(" ", "%20", $value);
        $url = config('app.api') . "/product/{$value}/{$attr}";
        $data = Api::data($url, $request);
        return isset($data["product"]) ? $data["product"] : null;
    }

    public function images(Int $total = 0, $no_img)
    {
        $codigo_ima = $this->codigo_ima;
        $name = "/IMAGEN/{$codigo_ima[0]}/{$codigo_ima}";
        $images = [];
        if (file_exists(public_path() . "{$name}.jpg"))
            $images[] = asset("{$name}.jpg");
        if ($total == 0) {
            for ($i = 1; $i <= 10; $i++) {
                if (file_exists(public_path() . "{$name}-{$i}.jpg"))
                    $images[] = asset("{$name}-{$i}.jpg");
            }
            if (empty($images))
                $images[] = $no_img;
            return $images;
        }
        if (empty($images))
            $images[] = $no_img;
        return "<img src='{$images[0]}' alt='{$this->stmpdh_tex}' onerror=\"this.src='{$no_img}'\" class='w-100'/>";
    }

    /* ================== */
    public static function create($attr) {
        $flagNew = false;
        $code = str_replace("." , "__", $attr["stmpdh_art"]);
        $code = str_replace(" " , "_", $code);
        $model = self::find($code);
        if (!$model) {
            $flagNew = true;
            $model = new self;
            $model->_id = $code;
        }
        if ($flagNew) {
            $model->search = $attr['stmpdh_art'] . " " . $attr['stmpdh_tex'];
            if (isset($attr['stmpdh_art']))
                $model->stmpdh_art = $attr['stmpdh_art'];
            if (isset($attr['use']))
                $model->use = $attr['use'];
            if (isset($attr['codigo_ima']))
                $model->codigo_ima = $attr['codigo_ima'];
            if (isset($attr['stmpdh_tex'])) {
                $description = $attr['stmpdh_tex'];
                if (str_contains($attr['stmpdh_tex'], 'PARA')) {
                    list($description, $application) = explode('PARA', $attr['stmpdh_tex']);// Espero que haya 1 solo
                    $model->application = [
                        "PARA {$application}"
                    ];
                }
                $model->stmpdh_tex = trim($description);
                $model->name_slug = Str::slug(trim($description));
            }
            if (isset($attr['precio']))
                $model->precio = $attr['precio'];
            if (isset($attr['web_marcas'])) {
                $model->web_marcas = [
                    ['brand' => $attr['web_marcas'], 'slug' => Str::slug($attr['web_marcas'])]
                ];
            }
            if (isset($attr['subparte'])) {
                $model->subparte = [
                    "code" => $attr['cod_subparte'],
                    "name" => $attr['subparte']
                ];
            }
            if (isset($attr['parte']))
                $model->parte = $attr['parte'];
            if (isset($attr['modelo_anio']))
                $model->modelo_anio = $attr['modelo_anio'];
            if (isset($attr['cantminvta']))
                $model->cantminvta = $attr['cantminvta'];
            if (isset($attr['fecha_ingr']))
                $model->fecha_ingr = $attr['fecha_ingr'];
            if (isset($attr['nro_original']))
                $model->nro_original = $attr['nro_original'];
            if (isset($attr['stock_mini']))
                $model->stock_mini = $attr['stock_mini'];
            if (isset($attr['liquidacion']))
                $model->liquidacion = $attr['liquidacion'];
            if (isset($attr['max_ventas']))
                $model->max_ventas = $attr['max_ventas'];
        } else {
            if (isset($attr['web_marcas'])) {
                $web_marcas = $model->web_marcas;
                $web_marcas[] = ['brand' => $attr['web_marcas'], 'slug' => Str::slug($attr['web_marcas'])];
                $model->web_marcas = $web_marcas;
            }
            if (isset($attr['stmpdh_tex'])) {
                $description = $attr['stmpdh_tex'];
                if (str_contains($attr['stmpdh_tex'], 'PARA')) {
                    list($description, $application) = explode('PARA', $attr['stmpdh_tex']);// Espero que haya 1 solo
                    if (!empty($application)) {
                        $applications = $model->application;
                        $applications[] = "PARA {$application}";
                        $model->application = $applications;
                    }
                }
            }
        }
        if (isset($attr['active'])) {

            $model->active = $attr['active'];

        }
        $model->save();

        return $model;
    }

    public function price() {

        $precio = $this->precio;
        if(session()->has('markup') && session()->get('markup') != "costo") {

            $discount = auth()->guard('web')->user()->discount / 100;
            $precio += $discount * $precio;

        }
        return "$ " . number_format($precio, 2, ".", ".");

    }

    public static function soap($use) {
        $msserver="181.170.160.91:9090";

        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

        $param = array( "pSPName" => "ConsultaStock", "pParamList" => '$ARTCOD;' . $use, "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl');
            $result = $client->call('EjecutarSP_String', $param, '', '', false, true);
            if ($client->fault) {
                return -1;
            } else {
                $err = $client->getError();
                if ($err)
                    return -2;
                else {
                    $cadena = explode(",", $result["EjecutarSP_StringResult"]);
                    if ($cadena[2] > 0 )
                        return $cadena[2];
                    else
                        return $cadena[2];
                }
            }
        } catch (\Throwable $th) {
            return -3;
        }
    }

    public static function updateCollection(Bool $fromCron = false) {

        set_time_limit(0);
        //\Artisan::call('down');
        //\Artisan::call('up');
        $model = new self;
        $properties = $model->getFillable();
        $errors = [];
        $source = implode('/', ['/var/www/pedidos', config('app.files.folder'), configs("FILE_PRODUCTS", config('app.files.products'))]);
        if (file_exists($source)) {

            self::removeAll(true);
            Subpart::removeAll();
            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'STMPDH_ARTCOD') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) continue;
                try {
                    $elements[] = true;
                    $elements[] = [];
                    $data = array_combine($properties, $elements);
                    $data["cantminvta"] = floatval(str_replace("," , ".", $data["cantminvta"]));
                    $data["usr_stmpdh"] = floatval(str_replace("," , ".", $data["usr_stmpdh"]));
                    $data["precio"] = floatval(str_replace("," , ".", $data["precio"]));
                    $data["stock_mini"] = intval($data["stock_mini"]);
                    if (strpos($data["fecha_ingr"], " ") !== false) {

                        $auxDate = explode(" ", $data["fecha_ingr"]);
                        list($d, $m, $a) = explode("/", $auxDate[0]);
                        $data["fecha_ingr"] = date("Y-m-d H:i:s" , strtotime("{$a}/{$m}/{$d} {$auxDate[1]}"));

                    } else {

                        list($d, $m, $a) = explode("/", $data["fecha_ingr"]);
                        $data["fecha_ingr"] = date("Y-m-d", strtotime("{$a}/{$m}/{$d}"));

                    }
                    $product = self::create($data);
                    $part = Part::firstOrNew(
                        ['name' => $data['parte']]
                    );
                    $part->save();
                    $subpart = Subpart::where("code", $product->subparte["code"])->first();
                    if (!$subpart) {
                        Subpart::create([
                            "code" => $product->subparte["code"],
                            "name" => $product->subparte["name"],
                            "name_slug" => Str::slug($product->subparte["name"], "-"),
                            "family_id" => $part->family_id,
                            "part_id" => $part->id
                        ]);
                    }

                } catch (\Throwable $th) {

                    $errors[] = $elements;

                }
            }
            fclose($file);

            $log = fopen(public_path()."/file/log_update.txt", "w") or die("Unable to open file!");

            fwrite($log, date("Y-m-d H:i:s"));

            fclose($log);

            if ($fromCron) {

                return responseReturn(true, 'Productos insertados: '.self::count().' / Errores: '.count($errors));

            }

            return responseReturn(false, 'Productos insertados: '.self::count().' / Errores: '.count($errors));

        }

        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }

        return responseReturn(true, 'Archivo no encontrado', 1, 400);

    }

    public function getName() {
        return 'products';
    }
}
