<?php

namespace App\Imports;

use App\Models\ApplicationTmp;
use App\Models\ApplicationBrand;
use App\Models\ApplicationModel;
use App\Models\ApplicationYear;
use App\Models\ApplicationProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SecondSheetImport implements ToModel, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[0] != 'SKU') {
            $element = array();
            $code = str_replace("." , "__", $row[4]);
            $code = str_replace(" " , "_", $code);
            $product = Product::where('_id', $code)->first();
            if ($product) {

                $applicationBrand = ApplicationBrand::firstOrNew(
                    array(
                        'name' => trim($row[1]),
                        'slug' => \Str::slug(trim($row[1]))
                    )
                );
                $applicationBrand->save();
                $applicationModel = ApplicationModel::firstOrNew(
                    array(
                        'brand_id' => $applicationBrand->id,
                        'name' => trim($row[2]),
                        'slug' => \Str::slug(trim($row[2]))
                    )
                );
                $applicationModel->save();
                $applicationYear = ApplicationYear::firstOrNew(
                    array(
                        'brand_id' => $applicationBrand->id,
                        'model_id' => $applicationModel->id,
                        'name' => trim($row[3]),
                        'slug' => \Str::slug(trim($row[3]))
                    )
                );
                $applicationYear->save();
                $data = array(
                    'sku' => $row[0],
                    'year_id' => $applicationYear->id,
                    'model_id' => $applicationModel->id,
                    'brand_id' => $applicationBrand->id,
                    'price' => 0,
                    'status' => true,
                    'title' => trim($row[5]),
                    'description' => null
                );
                $element[] = array(
                    'product_id' => $product->id,
                    'type' => 'TRASERA'
                );
                $applicationTmp = new ApplicationTmp($data);
                $applicationTmp->save();
                $products = array();
                foreach($element AS $p) {

                    $p['brand_id'] = $applicationBrand->id;
                    $p['model_id'] = $applicationModel->id;
                    $p['year_id'] = $applicationYear->id;
                    $products[] = new ApplicationProduct($p);

                }
                $applicationTmp->products()->saveMany($products);
                return $applicationTmp;

            }
        }
    }
}
