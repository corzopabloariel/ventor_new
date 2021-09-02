<?php

namespace App\Imports;

use App\Models\ApplicationTmp;
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
            $data = array(
                'sku' => $row[0],
                'brand' => trim($row[1]),
                'model' => trim($row[2]),
                'year' => trim($row[3]),
                'type' => 'TRAS',
                'element' => array(
                    'T' => array(
                        'code' => $row[4],
                        'price' => $row[5],
                        'stock' => intval($row[6])
                    )
                ),
                'price' => $row[7],
                'status' => $row[8] == "Activa",
                'title' => trim($row[10]),
                'description' => $row[11]
            );
            return ApplicationTmp::create($data);
        }
    }
}
