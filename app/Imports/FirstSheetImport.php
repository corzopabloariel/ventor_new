<?php

namespace App\Imports;

use App\Models\ApplicationTmp;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FirstSheetImport implements ToModel, WithCalculatedFormulas
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
            if (!empty($row[4])) {
                $product = Product::find($row[4]);
                if ($product) {
                    $element['C'] = array(
                        'code' => $row[4]
                    );
                }
            }
            if (!empty($row[5])) {
                $product = Product::find($row[5]);
                if ($product) {
                    $element['A'] = array(
                        'code' => $row[5]
                    );
                }
            }
            if (!empty($element)) {

                $data = array(
                    'sku' => $row[0],
                    'brand' => trim($row[1]),
                    'model' => trim($row[2]),
                    'year' => trim($row[3]),
                    'type' => 'DEL',
                    'element' => $element,
                    'price' => 0,
                    'status' => true,
                    'title' => trim($row[6]),
                    'description' => null
                );
                $element = null;
                try {
                    $element = ApplicationTmp::create($data);
                } catch (\Throwable $th) {
                    $logFile = '/var/www/html/laravel/ventor/storage/logs/application_tmp.log';
                    if(!file_exists($logFile)){
                        $handle = fopen($logFile, 'w');
                        $date = date('Y-m-d H:i:s').' - '.$data['sku'];
                        fwrite($handle, $date);
                    } else {
                        $handle = fopen($logFile, 'a');
                        $date = "\n".date('Y-m-d H:i:s').' - '.$data['sku'];
                        fwrite($handle, $date);
                    }
                }
                return $element;
            }
        }
    }
}
