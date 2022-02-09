<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Part;
use App\Models\Subpart;
use App\Http\Resources\ProductResource;

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
    /* ================== */
    public function part() {

        return $this->belongsTo('App\Models\Part','part_id','id');

    }
    public function subpart() {

        return $this->belongsTo('App\Models\Subpart','subpart_id','id');

    }
    public function brands() {

        return $this->belongsToMany(Brand::class, 'products_brand', 'product_id', 'brand_id');

    }
    public function models() {

        return $this->belongsToMany(ModelBrand::class, 'products_model', 'product_id', 'model_id');

    }
    public function applications() {

        return $this->belongsToMany(ApplicationBasic::class, 'products_application', 'product_id', 'application_id');

    }
    /* ================== */
    public static function getAll(String $attr = "_id", String $order = "ASC")
    {
        return self::orderBy($attr, $order)->get();
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
        $source = implode('/', [configs("FOLDER"), config('app.files.folder'), configs("FILE_PRODUCTS", config('app.files.products'))]);
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
    public static function one($request, String $code) {

        set_time_limit(600);
        $product = self::where('_id', $code)->first();
        if ($product) {

            $productResource = new ProductResource($product);
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'total'     => array(
                    'brands'    => count($productResource->brands),
                    'pages'     => 1,
                    'products'  => 1
                ),
                'brands'    => \App\Http\Resources\BrandResource::collection($product->brands),
                'page'      => 1,
                'products'  => array($productResource),
                'request'   => array(
                    'part'      => $product->part->family->name_slug,
                    'subpart'   => $product->subpart->name_slug
                ),
                'elements'  => array(
                    'part'      => $product->part->family->name,
                    'subpart'   => $product->subpart->name
                ),
            );

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Producto no encontrado'
        );

    }
    /** */
    public static function price($request) {

        set_time_limit(600);
        $code = $request->code;
        $user = empty($request->userId) ? NULL : User::find($request->userId);
        $product = self::where('_id', $code)->first();
        if ($product) {

            $priceMarkup = null;
            if ($user) {

                $priceMarkup = empty($user->discount) ?
                    null :
                    round($product->precio * (1 + ($user->discount / 100)), 2);

            }
            $data = array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'code'      => $code
            );
            if (!$request->on) {

                $data['price'] = array(
                    'float'     => (float) $product->precio,
                    'string'    => '$ '.number_format($product->precio, 2, ',', '.')
                );

            }
            if (!empty($priceMarkup) && $request->on) {

                $data['priceMarkup'] = array(
                    'markup'    => ($user->discount / 100),
                    'float'     => $priceMarkup,
                    'string'    => '$ ' . number_format($priceMarkup, 2, ',', '.')
                );

            }
            return $data;

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Producto no encontrado'
        );

    }
    /** */
    public static function stock($request) {

        set_time_limit(600);
        $code = $request->code;
        $user = empty($request->userId) ? NULL : User::find($request->userId);
        $product = self::where('_id', $code)->first();
        if ($product) {

            $msserver="181.170.160.91:9090";
            $param = array( "pSPName" => "ConsultaStock", "pParamList" => '$ARTCOD;' . $product->use, "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
            $stock = 0;
            try {
                $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl');
                $result = $client->call('EjecutarSP_String', $param, '', '', false, true);
                if ($client->fault) {

                    return
                    array(
                        'error'     => true,
                        'status'    => 506,
                        'message'   => 'Información no disponible en este momento'
                    );

                } else {
                    $err = $client->getError();
                    if ($err) {

                        return
                        array(
                            'error'     => true,
                            'status'    => 502,
                            'message'   => 'Información no disponible en este momento',
                            'data'      => $param
                        );

                    } else {

                        $cadena = explode(",", $result["EjecutarSP_StringResult"]);
                        $stock = !isset($cadena[2]) ? 0 : (int) $cadena[2];
                        $color = $message = '';
                        $stockMinProduct = (int) $product->stock_mini;
                        if ($stock > $stockMinProduct) {

                            $color = '--available';
                            $message = 'Stock disponible';

                        } else if ($stockMinProduct > $stock && $stock > 0) {

                            $color = '--warning';
                            $message = 'Stock inferior o igual a cantidad crítica';

                        } else {

                            $color = '--danger';
                            $message = 'Stock no disponible';

                        }
                        if (!($user && $user->isAdminUser)) {

                            $stock = true;

                        }
                        return 
                        array(
                            'error'     => false,
                            'status'    => 202,
                            'message'   => $message,
                            'color'     => $color,
                            'stock'     => $stock
                        );

                    }
                }
            } catch (\Throwable $th) {

                return
                array(
                    'error'     => true,
                    'status'    => 500,
                    'message'   => $th->getMessage()
                );

            }

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Producto no encontrado'
        );

    }
    /** */
    private static function getBrands($products) {

        set_time_limit(600);
        $products = $products->pluck('id');
        $brands = ProductBrand::whereIn('product_id', $products)
            ->select('brands.name', 'brands.slug')
            ->distinct()
            ->join('brands', 'brands.id', '=', 'products_brand.brand_id')
            ->orderBy('brands.name', 'ASC')
            ->get();
        return $brands;

    }
    /** */
    public static function onlyBrands($request, $data = array()) {

        try {

            $products = empty($data) || (!empty($data) && !isset($data['products'])) ? self::where('_id', '!=', '') : $data['products'];
            $slug = '';
            $response = array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'total'     => array(),
            );
            if ($request->has('search') && !empty($request->search)) {

                $data['search'] = $request->search;
                $search_elem = explode("+", strtoupper($request->search));
                $products = $products->where(function ($q) use ($search_elem) {

                    foreach ($search_elem as $value) {

                        $q->where(\DB::raw("CONCAT(`stmpdh_tex`, ' ', `use`, ' ', `stmpdh_art`)"), "LIKE", "%{$value}%");

                    }

                });

            }
            if ($request->has("type") && $request->get('type') == "liquidacion") {

                $products = $products->where("liquidacion", true);

            }
            if ($request->has('part') && !empty($request->part)) {

                $data['part'] = $request->part;
                $family = Family::where('name_slug', $request->part)->first();
                $parts = $family->parts->map(function($item) {
                    return $item->name;
                })->toArray();
                $products = $products->whereIn("products.part_id", $parts);
                $response['elements']['part'] = $family->name;
                $slug .= 'parte:'.$request->part;

            }
            if ($request->has('subpart') && !empty($request->subpart)) {

                $data['subpart'] = $request->subpart;
                $subpart = Subpart::where('name_slug', $request->subpart)->first();
                $products = $products->where("subpart_id", $subpart->id);
                $response['aux'] = $subpart;
                $response['elements']['subpart'] = $subpart->name;
                $slug .= '/subparte:'.$request->subpart;

            }
            if ($request->has("type") && $request->get('type') == "nuevos" && $request->has('userId')) {

                $user = User::find($request->get('userId'));
                $date = strtotime($user->start);
                $aux = $user;
                $dateStart = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
                $date = strtotime($user->end);
                $dateEnd = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
                $products = $products->whereBetween('fecha_ingr', [$dateStart, $dateEnd]);

            }
            $productsBrand = (clone $products);
            $brands = self::getBrands($productsBrand);
            if (!$request->has('simple')) {

                $response['brands'] = $brands;
                $response['total']['brands'] = $brands->count();

            }
            return $response;

        } catch (\Throwable $th) {

            return
            array(
                'error'     => true,
                'status'    => 500,
                'message'   => $th->getMessage()
            );

        }

    }
    public static function gets($request, $data = array()) {

        set_time_limit(600);
        try {

            $products = empty($data) || (!empty($data) && !isset($data['products'])) ? self::where('_id', '!=', '') : $data['products'];
            $products = $products->where('precio', '>', '0');
            $slug = '';
            $response = array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'total'     => array(
                    'pages'     => 0,
                    'products'  => 0
                ),
                'elements'  => array(),
                'page'      => 0,
                'products'  => NULL
            );
            if ($request->has('search') && !empty($request->search)) {

                $data['search'] = $request->search;
                $search_elem = explode("+", strtoupper($request->search));
                $products = $products->where(function ($q) use ($search_elem) {

                    foreach ($search_elem as $value) {

                        $q->where(\DB::raw("CONCAT(`stmpdh_tex`, ' ', `use`, ' ', `stmpdh_art`)"), "LIKE", "%{$value}%");

                    }

                });

            }
            if ($request->has("type") && $request->get('type') == "liquidacion") {

                $products = $products->where("liquidacion", true);

            }
            if ($request->has('part') && !empty($request->part)) {

                $data['part'] = $request->part;
                $family = Family::where('name_slug', $request->part)->first();
                $parts = $family->parts->pluck('id');
                $products = $products->whereIn("products.part_id", $parts);
                $response['elements']['part'] = $family->name;
                $slug .= 'parte:'.$request->part;

            }
            if ($request->has('subpart') && !empty($request->subpart)) {

                $data['subpart'] = $request->subpart;
                $subpart = Subpart::where('name_slug', $request->subpart)->first();
                $products = $products->where("subpart_id", $subpart->id);
                $response['aux'] = $subpart;
                $response['elements']['subpart'] = $subpart->name;
                $slug .= '/subparte:'.$request->subpart;

            }
            if ($request->has("type") && $request->get('type') == "nuevos" && $request->has('userId')) {

                $user = User::find($request->get('userId'));
                $date = strtotime($user->start);
                $aux = $user;
                $dateStart = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
                $date = strtotime($user->end);
                $dateEnd = Carbon::createFromDate(date("Y", $date), date("m", $date), date("d", $date));
                $products = $products->whereBetween('fecha_ingr', [$dateStart, $dateEnd]);

            }
            if ($request->has('brand') && !empty($request->brand)) {

                if (empty($slug)) {

                    $slug .= 'productos';

                }
                $brand = $request->brand;
                $data['brand'] = $brand;
                $products = $products->whereHas('brands', function ($query) use ($brand) {

                    $query
                        ->where('slug', $brand);

                });
                $brand = Brand::where('slug', $brand)->first();
                $response['elements']['brand'] = $brand->name;
                $slug .= '__'.$request->brand;

            }
            $paginate = $request->has('paginate') ? (int) $request->get('paginate') : 10;
            $page = $request->has('page') ? (int) $request->get('page') : 1;
            $orderBy = $request->has('orderBy') ? $request->get('orderBy') : 'code';
            $orderByNameReal = array('code' => '_id', 'name' => 'stmpdh_tex');
            if (!isset($orderByNameReal[$orderBy])) {

                $orderBy = 'code';

            }
            $total = $products->count();
            $totalPages = ceil($total / $paginate);
            if (isset($data['products'])) { unset($data['products']); }
            $response['total']['pages'] = $totalPages;
            $response['total']['products'] = $total;
            $response['page'] = $page;
            $response['products'] = ProductResource::collection(
                $products->
                    join('subparts', 'products.subpart_id', '=', 'subparts.id')->
                    orderBy('subparts.code', 'ASC')->
                    orderBy($orderByNameReal[$orderBy])->
                    paginate($paginate)
            );
            if (!$request->has('simple')) {

                $response['title'] = $total == 0 ? 'Sin resultados para tu búsqueda' : '<span>'.$total.'</span> producto'.($total > 1 ? 's' : '');
                if (empty($slug)) {

                    $slug .= 'productos';

                }
                if ($request->has('search') && !empty($request->search)) {

                    $slug .= ','.$request->search;
                    $response['elements']['search'] = str_replace('+', ' ', $request->search);

                }
                $slug .= '?orderBy='.$orderBy;
                if ($request->has("type") && ($request->get("type") == 'liquidacion' || $request->get("type") == 'nuevos')) {

                    $response['elements']['type'] = $request->get("type") == 'liquidacion' ? 'Productos en liquidación' : 'Productos nuevos';
                    $response['request']['type'] = $request->get("type");
                    $slug .= '&type='.$request->get("type");

                }
                if ($page != 1) {

                    $slug .= '&page='.$page;

                }
                $response['slug'] = $slug;

            }
            return $response;

        } catch (\Throwable $th) {

            return
            array(
                'error'     => true,
                'status'    => 500,
                'message'   => $th->getMessage()
            );

        }

    }
    /** */
    public static function erase($request, String $code)
    {
        if ($request->isJson()) {

            $product = self::where('_id', $code)->first();
            if ($product) {

                $product->delete();
                return response(
                    array(
                        'error'     => false,
                        'status'    => 205,
                        'message'   => 'Producto eliminado'
                    ),
                    205
                );

            }
            return response(
                array(
                    'error'     => true,
                    'status'    => 404,
                    'message'   => 'Producto no encontrado'
                ),
                404
            );

        } else {

            return response(
                array(
                    'error'     => true,
                    'status'    => 401,
                    'message'   => 'Sin autorización'
                ),
                401
            );

        }
    }
    /** */
    public static function createOrUpdate($request, Array $attributes, Bool $checkProduct = true)
    {
        if (!isset($attributes['test']) || isset($attributes['test']) && !$attributes['test']) {

            if ($checkProduct) {

                $code = str_replace("." , "__", $attributes['stmpdh_art']);
                $code = str_replace(" " , "_", $code);
                $product = self::where('_id', $code)->first();
                if ($product) {

                    return response(
                        array(
                            'error' => true,
                            'status' => 418,
                            'message' => 'Sos boludo, el producto ya existe :)'
                        ),
                        418
                    );

                }

            }
            $product = (new self)->attributes($attributes);

        } else {

            $product = self::first();

        }
        return self::one($request, $product->_id);
    }
    /** */
    public function attributes($attr, Bool $new = false)
    {
        $model = $this;
        if ($new) {

            $code = str_replace("." , "__", $attr["stmpdh_art"]);
            $code = str_replace(" " , "_", $code);
            $model->_id = $code;

        }
        $model->search = implode(' ', array($attr['stmpdh_art'], $attr['stmpdh_tex']));
        $model->stmpdh_art = $attr['stmpdh_art'];
        $model->use = $attr['use'];
        $model->codigo_ima = $attr['codigo_ima'];
        $model->precio = $attr['precio'];
        $model->modelo_anio = $attr['modelo_anio'];
        $model->cantminvta = $attr['cantminvta'];
        $model->fecha_ingr = $attr['fecha_ingr'];
        $model->nro_original = $attr['nro_original'] ?? NULL;
        $model->stock_mini = $attr['stock_mini'];
        $model->liquidacion = $attr['liquidacion'] == 'S';
        $model->max_ventas = $attr['max_ventas'];
        if (isset($attr['subparte']) && isset($attr['cod_subparte'])) {

            $model->subparte = [
                "code" => $attr['cod_subparte'],
                "name" => $attr['subparte']
            ];

        }
        if (isset($attr['parte'])) {

            $model->parte = $attr['parte'];

        }
        if (isset($attr['stmpdh_tex'])) {

            $description = $attr['stmpdh_tex'];
            if (str_contains($attr['stmpdh_tex'], ' PARA ')) {

                list($description, $application) = explode(' PARA ', $attr['stmpdh_tex']);
                if (!empty($application)) {

                    $applications = $model->application;
                    $searchApplication = collect($applications)->search("PARA {$application}");
                    if (empty($searchApplication)) {

                        $applications[] = "PARA {$application}";
                        $model->application = $applications;

                    }

                }
            }
            $model->stmpdh_tex = trim($description);
            $model->name_slug = Str::slug(trim($description));

        }
        if (isset($attr['web_marcas'])) {

            $web_marcas = $model->web_marcas;
            $searchWebMarcas = collect($web_marcas)->first(function ($value, $key) use ($attr) {
                return $value['brand'] = $attr['web_marcas'];
            });
            if (empty($searchWebMarcas)) {

                $web_marcas[] = ['brand' => $attr['web_marcas'], 'slug' => Str::slug($attr['web_marcas'])];
                $model->web_marcas = $web_marcas;

            }

        }
        if (isset($attr['active'])) {

            $model->active = $attr['active'];

        }
        $model->save();

        $part = (new Part)->attributes(
            [
                'name' => $attr['parte']
            ], true);
        $subPart = (new Subpart)->attributes(
            [
                'code' => $model->subparte["code"],
                'name' => $model->subparte['name'],
                'name_slug' => Str::slug($model->subparte['name'], '-'),
                'family_id' => $part->family_id,
                'part_id' => $part->id
            ], true);

        return $model;
    }
}
