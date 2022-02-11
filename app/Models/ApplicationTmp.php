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
        return 'http://pedidos.ventor.com.ar/'.$imageBase.'.jpg';

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
    public static function elements($data) {

        if (!isset($data['brand'])) {

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
            isset($data['brand']) &&
            !isset($data['model'])
        ) {

            $applicationBrand = ApplicationBrand::find($data['brand']);
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
            isset($data['brand']) &&
            isset($data['model']) &&
            !isset($data['year'])
        ) {

            $applicationModel = ApplicationModel::find($data['model']);
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
            isset($data['brand']) &&
            isset($data['model']) &&
            isset($data['year'])
        ) {

            $applicationBrand = ApplicationBrand::find($data['brand']);
            $applicationModel = ApplicationModel::find($data['model']);
            $applicationYear = ApplicationYear::find($data['year']);
            $applications = $applicationYear->products->groupBy('application_id')->values();
            $products = array();
            $request = new \Illuminate\Http\Request();
            $request->setMethod('POST');
            $request->request->add(
                array('image' => 1)
            );
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
                    'brand' => $data['brand'],
                    'model' => $data['model'],
                    'year'  => $data['year']
                )
            );

        }

    }
}
