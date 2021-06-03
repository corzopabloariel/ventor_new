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
    public function user()
    {
        $user = \App\Models\User::where('uid', $this->_id)->first();
        if (!$user) {
            $user = \App\Models\User::where('docket', $this->nrocta)->first();
            $user->fill(['uid' => $this->_id]);
            $user->save();
        }
        return $user;
    }
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
    public static function getAll(String $attr = "_id", String $order = "ASC", $vndor = null)
    {
        if (empty($vndor))
            return self::orderBy($attr, $order)->get();
        return self::where("vendedor.code", $vndor)->orderBy($attr, $order)->get();
    }

    public static function one(String $_id, String $attr = "_id")
    {
        return self::where($attr, $_id)->first();
    }

    /* ================== */
    public static function create($attr)
    {
        $model = self::one($attr['nrocta'], "nrocta");
        if (!$model) {
            $model = new self;
            $model->nrocta = $attr['nrocta'];
        }
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
                'code' => $attr['vnddor'],
                'nombre' => $attr['descr_003'], 
                'telefono' => $attr['nrotel'],
                'email' => $attr['camail']
            ];
        }
        if (isset($attr['transportista'])) {

            $model->transportista = [
                'code' => $attr['transportista'],
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

    public function clean($n)
    {
        return trim($n);
    }

    public function soap($cliente_action) {
        $data = ["title" => "", "soap" => null];
        switch ($cliente_action) {
            case "analisis-deuda":
                $data["title"] = "Análisis de deuda";
                $soap = self::analisisDeuda();
                if ($soap) {
                    $soap = str_replace("Row", "tr", $soap);
                    $soap = str_replace("<Root>", "", $soap);
                    $soap = str_replace("</Root>", "", $soap);
                    $soap = str_replace("cod_aplicacion", "td", $soap);
                    $soap = str_replace("nro_aplicacion", "td", $soap);
                    $soap = str_replace("codigo", "td", $soap);
                    $soap = str_replace("numero", "td", $soap);
                    $soap = str_replace("cuota", "td", $soap);
                    $soap = str_replace("Cod_cliente", "td", $soap);
                    $soap = str_replace("Cliente", "td", $soap);
                    $soap = str_replace("Importe", "td", $soap);
                    $soap = str_replace("Vencimiento", "td", $soap);
                    $soap = str_replace("Emision", "td", $soap);
                    $soap = str_replace("Vendedor", "td", $soap);
                    $soap = str_replace("Comprobante", "td", $soap);
                    $soap = str_replace("<td />", "<td></td>", $soap);
                    $soap = trim($soap);
                    $soap = utf8_encode($soap);
                }
                if (!empty($soap)) {
                    $soap = str_replace("</tr>", "", $soap);
                    $soap = str_replace("</td>", "", $soap);
                    $soap = explode("<tr>", $soap);
                    $soap = collect($soap)->map(function($item) {
                        if (empty($item))
                            return null;
                        try {
                            $item = explode("<td>", $item);
                            $item = array_map("self::clean", $item);
                            $item = array_diff($item, array("", null));
                            $item = array_values($item);
                            $name = "VT{$item[2]}{$item[3]}.PDF";
                            $item[] = "<a class='btn btn-danger' target='blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";
                            $item = array_combine(["aplicacion", "nroAplicacion", "codigo", "numero", "cuota", "codCliente", "cliente", "importe", "vencimiento", "emision", "vendedor", "comprobante", "pdf"], $item);
                            $item["importeNumber"] = floatval($item["importe"]);
                            $item["importe"] = ($item["importeNumber"] < 0) ? "-$ " . number_format($item["importeNumber"] * -1, 2, ",", ".") : "$ " . number_format($item["importeNumber"], 2, ",", ".");
                            return $item;
                        } catch (\Throwable $th) {
                            return null;
                        }
                    })->filter(function($value, $key) {
                        return !empty($value);
                    })->toArray();
                    $total = collect($soap)->map(function($item) {
                        return $item["importeNumber"];
                    })->sum();
                    $soap = ["soap" => $soap, "total" => $total];
                }
                break;
            case "faltantes":
                $data["title"] = "Faltantes";
                $soap = self::faltantes();
                if ($soap) {
                    $soap = explode("<Row>" , $soap);
                    for($i = 0; $i < count($soap) ; $i ++) {
                        if(strpos($soap[$i], "STOCK_CENTRAL") === false)
                            $soap[$i] = str_replace("</Row>" , "<td></td></Row>", $soap[$i]);
                    }
                    $soap = implode("<Row>", $soap);
                    
                    $soap = str_replace("<Root>", "", $soap);
                    $soap = str_replace("</Root>", "", $soap);
                    $soap = str_replace("Row", "tr", $soap);
                    $soap = str_replace("CUENTA", "td", $soap);
                    $soap = str_replace("ARTICULO", "td", $soap);
                    $soap = str_replace("DESCRIPCION", "td", $soap);
                    $soap = str_replace("FECHA", "td", $soap);
                    $soap = str_replace("PRECIO", "td", $soap);
                    $soap = str_replace("CANTIDAD", "td", $soap);
                    $soap = str_replace("TOTAL", "td", $soap);
                    $soap = str_replace("STOCK_CENTRAL", "td", $soap);
                    $soap = str_replace("<td />", "<td></td>", $soap);
                    $soap = trim($soap);
                    $soap = utf8_encode($soap);
                }
                if (!empty($soap)) {
                    $soap = str_replace("</tr>", "", $soap);
                    $soap = str_replace("</td>", "", $soap);
                    $soap = explode("<tr>", $soap);
                    $soap = collect($soap)->map(function($item) {
                        if (empty($item))
                            return null;
                        try {
                            $item = explode("<td>", $item);
                            $item = array_map("self::clean", $item);
                            $item = array_diff($item, array("", null));
                            $item = array_values($item);
                            $item = array_combine(["cuenta", "articulo", "descripcion", "fecha", "precio", "cantidad", "total", "stock"], $item);
                            $item["precio"] = "$ " . number_format($item["precio"], 2, ",", ".");
                            $item["total"] = "$ " . number_format($item["total"], 2, ",", ".");
                            $item["cantidad"] = intval($item["cantidad"]);
                            $item["stock"] = intval($item["stock"]);
                            return $item;
                        } catch (\Throwable $th) {
                            return null;
                        }
                    })->filter(function($value, $key) {
                        return !empty($value);
                    })->toArray();
                    $soap = ["soap" => $soap];
                }
                break;
            case "comprobantes":
                $data["title"] = "Comprobantes";
                $soap = self::comprobantes();
                if ($soap) {
                    $soap = str_replace("<Root>", "", $soap);
                    $soap = str_replace("</Root>", "", $soap);
                    $soap = str_replace("Row", "tr", $soap);
                    $soap = str_replace("Modulo", "td", $soap);
                    $soap = str_replace("Codigo", "td", $soap);
                    $soap = str_replace("Numero", "td", $soap);
                    $soap = str_replace("Emision", "td", $soap);
                    $soap = str_replace("Cuenta", "td", $soap);
                    $soap = str_replace("nombre", "td", $soap);
                    $soap = str_replace("Importe", "td", $soap);
                    $soap = str_replace("<td />", "<td></td>", $soap);
                    $soap = trim($soap);
                    $soap = utf8_encode($soap);
                }
                if (!empty($soap)) {
                    $soap = str_replace("</tr>", "", $soap);
                    $soap = str_replace("</td>", "", $soap);
                    $soap = explode("<tr>", $soap);
                    $soap = collect($soap)->map(function($item) {
                        if (empty($item))
                            return null;
                        try {
                            $item = explode("<td>", $item);
                            $item = array_map("self::clean", $item);
                            $item = array_diff($item, array("", null));
                            $item = array_values($item);
                            $name = "{$item[0]}{$item[1]}{$item[2]}.PDF";
                            $item[] = "<a class='btn btn-danger' target='blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";
                            $item = array_combine(["modulo", "codigo", "numero", "emision", "cuenta", "nombre", "importe", "pdf"], $item);
                            $item["importeNumber"] = floatval($item["importe"]);
                            $item["importe"] = ($item["importeNumber"] < 0) ? "-$ " . number_format($item["importeNumber"] * -1, 2, ",", ".") : "$ " . number_format($item["importeNumber"], 2, ",", ".");
                            return $item;
                        } catch (\Throwable $th) {
                            return null;
                        }
                    })->filter(function($value, $key) {
                        return !empty($value);
                    })->toArray();
                    $total = collect($soap)->map(function($item) {
                        return $item["importeNumber"];
                    })->sum();
                    $soap = ["soap" => $soap, "total" => $total];
                }
                break;
        }
        $data["soap"] = $soap;

        return $data;
    }

    public function analisisDeuda()
    {
        $msserver = "181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_CtaCte", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function faltantes()
    {
        $msserver="181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_Faltante", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function comprobantes()
    {
        $msserver="181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_Form", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
