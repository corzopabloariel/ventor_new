<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApplicationImport;
use App\Http\Resources\ApplicationBrandResource;
use App\Http\Resources\ApplicationModelResource;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ProductResource;

class ApplicationTmp extends Model
{
    use HasFactory;
    protected $table = 'application_tmp';

    protected $fillable = [
        'sku',
        'year_id',
        'model_id',
        'brand_id',
        'price',
        'status',
        'title',
        'description'
    ];

    protected $casts = [
        'element' => 'array',
        'status' => 'bool'
    ];
    public function products() {

        return $this->hasMany(ApplicationProduct::class, 'application_id', 'id');

    }
    public function getImageAttribute() {

        $products = $this->products;
        if (empty($products)) {

            return '';

        }
        $imageBase = "IMAGEN/{$products[0]->product->codigo_ima[0]}/{$products[0]->product->codigo_ima}";
        $imageBase = str_replace(' ', '%20', $imageBase);
        $images = array();
        if (file_exists(configs("FOLDER").'/'.$imageBase.'.jpg')) {

            $type = pathinfo(configs("FOLDER").'/'.$imageBase.'.jpg', PATHINFO_EXTENSION);
            $images = array(
                'base64'    => 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(configs("FOLDER").'/'.$imageBase.'-'.$i.'.jpg')),
                'url'       => 'http://pedidos.ventor.com.ar/'.$imageBase.'.jpg'
            );

        }
        if (empty($images)) {

            return '';

        }
        return $images['base64'];

    }

    public static function updateCollection(Bool $fromCron = false) {

        $model = new self;
        $applications = configs("EXCEL_APLICACIONES");
        $applications = explode('|', $applications);
        $applications = collect($applications)->map(function($document) {
            list($name, $file, $active) = explode('=', $document);
            return array(
                'name' => $name,
                'file' => $file,
                'active' => $active,
            );
        })->firstWhere('active', '1');
        if (empty($applications)) {
            return responseReturn(true, 'No hay archivo activo', 1, 400);
        }
        $source = implode('/', [configs("FOLDER"), 'file', $applications['file']]);
        if (file_exists($source)) {

            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            self::truncate();
            ApplicationBrand::truncate();
            ApplicationModel::truncate();
            ApplicationYear::truncate();
            ApplicationProduct::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Excel::import(new ApplicationImport, $source);

            if ($fromCron) {

                return responseReturn(true, 'Aplicaciones insertadas: '.self::count());

            }

            return responseReturn(false, 'Aplicaciones insertadas: '.self::count());
        }

        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }

        return responseReturn(true, 'Archivo no encontrado', 1, 400);
    }
    public static function elements($request) {

        if (!$request->has('brand')) {

            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'brands'    => ApplicationBrandResource::collection(ApplicationBrand::all()),
                'slug'      => 'aplicacion',
                'productsHTML'  => 'Seleccioná una marca'
            );

        }
        if (
            $request->has('brand') &&
            !$request->has('model')
        ) {

            $applicationBrand = ApplicationBrand::find($request->get('brand'));
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'models'    => ApplicationModelResource::collection($applicationBrand->models),
                'slug'      => 'aplicacion',
                'productsHTML'  => 'Seleccioná un modelo'
            );

        }
        if (
            $request->has('brand') &&
            $request->has('model') &&
            !$request->has('year')
        ) {

            $applicationModel = ApplicationModel::find($request->get('model'));
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'years'     => ApplicationModelResource::collection($applicationModel->years),
                'slug'      => 'aplicacion',
                'productsHTML'  => 'Seleccioná un año'
            );

        }
        if (
            $request->has('brand') &&
            $request->has('model') &&
            $request->has('year')
        ) {

            $applicationBrand = ApplicationBrand::find($request->get('brand'));
            $applicationModel = ApplicationModel::find($request->get('model'));
            $applicationYear = ApplicationYear::find($request->get('year'));
            $applications = $applicationYear->products->groupBy('application_id')->values();
            $products = array();
            foreach($applications AS $application) {
                $products[] = array(
                    'title'     => $application[0]->application->title,
                    'image'     => $application[0]->application->image,
                    'products'  => $application->map(function($item) use ($request) {
                        return array(
                            'type'      => $item->type,
                            'product'   => (new ProductResource($item->product))->toArray($request)
                        );
                    })
                );
            }
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'brands'    => ApplicationBrandResource::collection(ApplicationBrand::all()),
                'models'    => ApplicationModelResource::collection($applicationBrand->models),
                'years'     => ApplicationModelResource::collection($applicationModel->years),
                'products'  => $products,//,
                'slug'      => 'aplicacion:'.$applicationBrand->slug.','.$applicationModel->slug.','.$applicationYear->name,
                'request'   => array(
                    'brand' => $request->get('brand'),
                    'model' => $request->get('model'),
                    'year'  => $request->get('year')
                )
            );

        }

    }
}
