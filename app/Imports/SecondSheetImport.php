<?php

namespace App\Imports;

use App\Models\ApplicationTmp;
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
            $code = str_replace("." , "__", $row[4]);
            $code = str_replace(" " , "_", $code);
            $product = Product::find($code);
            if ($product) {
                $data = array(
                    'sku' => $row[0],
                    'brand' => trim($row[1]),
                    'model' => trim($row[2]),
                    'year' => trim($row[3]),
                    'type' => 'TRAS',
                    'element' => array(
                        'T' => array(
                            'code' => $row[4]
                        )
                    ),
                    'price' => 0,
                    'status' => true,
                    'title' => trim($row[5])
                );
                return ApplicationTmp::create($data);
            }
        }
    }
}
