<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashfile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'hashfile';

    protected $fillable = [
        'word',
        'hash',
        'total',
        'test'
    ];

    protected $casts = [
        'test' => 'bool'
    ];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return '<p class="text-truncate">'.\url('feed.precios/'.$this->hash).'/[EXTENSIÓN]</p><strong>Descargas:<strong> '.$this->total;
    }

    /* ================== */
    public static function create($attr) {
        $flagNew = false;
        $model = self::where('hash', $attr['hash'])->first();
        if (!$model) {
            $model = new self;
            $model->hash = $attr['hash'];
        }
        $model->word = $attr['word'];
        $model->total = $attr['total'] ?? 0;
        $model->test = $attr['test'] ?? false;
        $model->save();

        return $model;
    }

    public static function search(String $file, String $hash = null, String $ext = null) {
        $extValidate = [
            'dbf',
            'xls',
            'txt',
            'csv'
        ];
        $element = self::where('hash', $hash)->first();
        switch($file) {
            case 'precios';
                if ($element) {
                    if (in_array($ext, $extValidate)) {
                        $extension = [
                            'txt' => public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt',
                            'dbf' => public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf',
                            'xls' => public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls',
                            'csv' => public_path().'/file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv'
                        ];
                        if (file_exists($extension[$ext])) {
                            $element->fill(['total' => $element->total + 1]);
                            $element->save();
                            $document = $extension[$ext];
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="'.basename($document).'"');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($document));
                            readfile($document);
                            die;
                        } else {
                            return responseReturn(true, 'Archivo no encontrado', 1, 404);
                        }
                    } else {
                        return responseReturn(true, 'Extensión no válida', 1, 400);
                    }
                } else {
                    return responseReturn(true, 'Hash no válido', 1, 400);
                }
            break;
            default:
                return responseReturn(true, 'Acción no encontrada', 1, 400);
        }
    }
}
