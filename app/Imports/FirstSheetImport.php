<?php

namespace App\Imports;

use App\Models\ApplicationTmp;
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
            if (!empty($row[4]) || !empty($row[5]) || !empty(intval($row[6]))) {
                $element['C'] = array(
                    'code' => $row[4],
                    'price' => $row[5],
                    'stock' => intval($row[6])
                );
            }
            if (!empty($row[7]) || !empty($row[8]) || !empty(intval($row[9]))) {
                $element['A'] = array(
                    'code' => $row[7],
                    'price' => $row[8],
                    'stock' => intval($row[9])
                );
            }
            $data = array(
                'sku' => $row[0],
                'brand' => $row[1],
                'model' => $row[2],
                'year' => $row[3],
                'type' => 'DEL',
                'element' => $element,
                'price' => 0,
                'status' => true,
                'title' => trim($row[13]),
                'description' => trim($row[14])
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
