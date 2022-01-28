<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Ventor\Api;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Ventor\Ticket;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'part_id',
        'subpart_id',
        '_id',
        'use',
        'stmpdh_art',
        'codigo_ima',
        'stmpdh_tex',
        'name_slug',
        'precio',
        'cantminvta',
        'stock_mini',
        'max_ventas',
        'fecha_ingr',
        'liquidacion'
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

    public function getImagesAttribute() {
        $total = 1;
        for ($i = 1; $i <= 10; $i++) {
            if (file_exists("/var/www/html/public/IMAGEN/{$this->codigo_ima[0]}/{$this->codigo_ima}-{$i}.jpg")) {
                $total ++;
            }
        }
        return $total;
    }
    public function part() {

        return $this->belongsTo('App\Models\Part','part_id','id');

    }
    public function subpart() {

        return $this->belongsTo('App\Models\Subpart','subpart_id','id');

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
        $model = self::withTrashed()->where('_id', $code)->first();
        if (!$model) {

            $flagNew = true;
            $model = new self;
            $model->_id = $code;

        } else {

            $model->deleted_at = null;

        }
        if (isset($attr['part_id'])) {

            $model->part_id = $attr['part_id'];

        }
        if (isset($attr['subpart_id'])) {

            $model->subpart_id = $attr['subpart_id'];

        }
        if ($flagNew) {

            if (isset($attr['stmpdh_art'])) {

                $model->stmpdh_art = $attr['stmpdh_art'];

            }
            if (isset($attr['use'])) {

                $model->use = $attr['use'];

            }
            if (isset($attr['codigo_ima'])) {

                $model->codigo_ima = $attr['codigo_ima'];

            }
            if (isset($attr['stmpdh_tex'])) {

                $description = $attr['stmpdh_tex'];
                if (str_contains($attr['stmpdh_tex'], ' PARA ')) {

                    list($description, $application) = explode(' PARA ', $attr['stmpdh_tex']);
                    $application = ApplicationBasic::firstOrNew(
                        array(
                            'name' => trim($application),
                            'slug' => Str::slug(trim($application))
                        )
                    );
                    $application->save();

                }
                $model->stmpdh_tex = trim($description);
                $model->name_slug = Str::slug(trim($description));

            }
            if (isset($attr['precio'])) {

                $model->precio = $attr['precio'];

            }
            if (isset($attr['cantminvta'])) {

                $model->cantminvta = $attr['cantminvta'];

            }
            if (isset($attr['fecha_ingr'])) {

                $model->fecha_ingr = $attr['fecha_ingr'];

            }
            if (isset($attr['nro_original'])) {

                $model->nro_original = $attr['nro_original'];

            }
            if (isset($attr['stock_mini'])) {

                $model->stock_mini = $attr['stock_mini'];

            }
            if (isset($attr['liquidacion'])) {

                $model->liquidacion = $attr['liquidacion'];

            }
            if (isset($attr['max_ventas'])) {

                $model->max_ventas = $attr['max_ventas'];

            }

        } else {

            if (isset($attr['stmpdh_tex'])) {

                $description = $attr['stmpdh_tex'];
                if (str_contains($attr['stmpdh_tex'], ' PARA ')) {

                    list($description, $application) = explode(' PARA ', $attr['stmpdh_tex']);// Espero que haya 1 solo
                    if (!empty($application)) {

                        $application = ApplicationBasic::firstOrNew(
                            array(
                                'name' => trim($application),
                                'slug' => Str::slug(trim($application))
                            )
                        );
                        $application->save();

                    }

                }

            }

        }
        $model->save();
        if (isset($attr['web_marcas'])) {

            $brand = Brand::firstOrNew(
                array(
                    'name' => trim($attr['web_marcas']),
                    'slug' => Str::slug(trim($attr['web_marcas']))
                )
            );
            $brand->save();
            $productBrand = ProductBrand::firstOrNew(
                array(
                    'product_id' => $model->id,
                    'brand_id' => $brand->id
                )
            );
            $productBrand->save();

        }
        if (isset($attr['modelo_anio'])) {

            $modelBrand = ModelBrand::firstOrNew(
                array(
                    'name' => trim($attr['modelo_anio']),
                    'slug' => Str::slug(trim($attr['modelo_anio']))
                )
            );
            $modelBrand->save();
            $productModel = ProductModel::firstOrNew(
                array(
                    'product_id' => $model->id,
                    'model_id' => $modelBrand->id
                )
            );
            $productModel->save();

        }
        if (isset($application)) {

            $productApplication = ProductApplication::firstOrNew(
                array(
                    'product_id' => $model->id,
                    'application_id' => $application->id
                )
            );
            $productApplication->save();

        }
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
                    if (!isset($cadena[2]))
                        return 0;
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
        $properties = array(
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
            'max_ventas'
        );
        $errors = [];
        $source = implode('/', ['/var/www/pedidos', config('app.files.folder'), configs("FILE_PRODUCTS", config('app.files.products'))]);
        if (file_exists($source)) {

            (new self)::query()->delete();
            //Subpart::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            ProductBrand::truncate();
            ProductModel::truncate();
            Brand::truncate();
            ModelBrand::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
                //try {
                    $data = array_combine($properties, $elements);
                    $data["cantminvta"] = floatval(str_replace("," , ".", $data["cantminvta"]));
                    $data["usr_stmpdh"] = floatval(str_replace("," , ".", $data["usr_stmpdh"]));
                    $data["precio"] = floatval(str_replace("," , ".", $data["precio"]));
                    $data["stock_mini"] = intval($data["stock_mini"]);
                    $data["liquidacion"] = $data["liquidacion"] != 'N';
                    if (strpos($data["fecha_ingr"], " ") !== false) {

                        $auxDate = explode(" ", $data["fecha_ingr"]);
                        list($d, $m, $a) = explode("/", $auxDate[0]);
                        $data["fecha_ingr"] = date("Y-m-d H:i:s" , strtotime("{$a}/{$m}/{$d} {$auxDate[1]}"));

                    } else {

                        list($d, $m, $a) = explode("/", $data["fecha_ingr"]);
                        $data["fecha_ingr"] = date("Y-m-d", strtotime("{$a}/{$m}/{$d}"));

                    }
                    $part = Part::firstOrNew(
                        ['name' => $data['parte']]
                    );
                    $part->save();
                    $data['part_id'] = $part->id;
                    $subpart = Subpart::where("code", $data["cod_subparte"])->first();
                    if (!$subpart) {
                        $subpart = Subpart::create([
                            "code" => $data["cod_subparte"],
                            "name" => $data["subparte"],
                            "name_slug" => Str::slug($data["subparte"], "-"),
                            "family_id" => $part->family_id,
                            "part_id" => $part->id
                        ]);
                    }
                    $data['subpart_id'] = $subpart->id;
                    $product = self::create($data);
                /*} catch (\Throwable $th) {

                    $errors[] = $elements;

                }*/
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
