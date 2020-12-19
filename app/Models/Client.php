<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Client extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'clients';
    protected $primaryKey = '_id';
    protected $fillable = [
        'nrocta',
        'razon_social',
        'respon',
        'usrvtmcl',
        'usrvt_001',
        'usrvt_002',
        'direccion',
        'codpos',
        'descrp',
        'descr_001',
        'telefn',
        'nrofax',
        'direml',
        'nrodoc',
        'descr_002',
        'usrvt_003',
        'vnddor',
        'descr_003',
        'nrotel',
        'camail',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'NO',
        'transportista',
        'NO',
        'whatsapp',
        'instagram'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /* ================== */
    public static function removeAll()
    {
        try {
            self::truncate();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        if (isset($attr['nrocta']))
            $model->nrocta = $attr['nrocta'];
        if (isset($attr['razon_social']))
            $model->razon_social = $attr['razon_social'];
        if (isset($attr['respon']))
            $model->respon = $attr['respon'];
        if (isset($attr['usrvtmcl']))
            $model->usrvtmcl = $attr['usrvtmcl'];
        if (isset($attr['usrvt_001']))
            $model->usrvt_001 = $attr['usrvt_001'];
        if (isset($attr['usrvt_002']))
            $model->usrvt_002 = $attr['usrvt_002'];
        if (isset($attr['direccion'])) {
            $model->address = [
                'direccion' => $attr['direccion'],
                'codpos' => $attr['codpos'],
                'localidad' => $attr['descrp'],
                'provincia' => $attr['descr_001']
            ];
        }
        if (isset($attr['telefn']))
            $model->telefn = $attr['telefn'];
        if (isset($attr['nrofax']))
            $model->nrofax = $attr['nrofax'];
        if (isset($attr['direml']))
            $model->direml = $attr['direml'];
        if (isset($attr['nrodoc']))
            $model->nrodoc = $attr['nrodoc'];
        if (isset($attr['usrvt_003']))
            $model->usrvt_003 = $attr['usrvt_003'];
        if (isset($attr['vnddor'])) {
            $model->vendedor = [
                'cod' => $attr['vnddor'],
                'nombre' => $attr['descr_003'], 
                'telefono' => $attr['nrotel'],
                'email' => $attr['camail']
            ];
        }
        if (isset($attr['transportista'])) {

            $model->transportista = [
                'cod' => $attr['transportista'],
                'nombre' => $attr['descr_002']
            ];
        }
        if (isset($attr['whatsapp']))
            $model->whatsapp = $attr['whatsapp'];
        if (isset($attr['instagram']))
            $model->instagram = $attr['instagram'];
        $model->save();

        return $model;
    }
}
