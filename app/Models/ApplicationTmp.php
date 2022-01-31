<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApplicationImport;

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
        $source = implode('/', [env('ROUTE_FILE'), 'file', $applications['file']]);
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
}
