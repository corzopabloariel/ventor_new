<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClientResource;

class Client extends Model {

    const msserver = "181.170.160.91:9090";
    protected $fillable = [
        'user_id',
        'transport_id',
        'nrocta',
        'data'
    ];
    protected $casts = [
        'data'  => 'array'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public function getTransportAttribute() {

        if (empty($this->transportista)) {

            return null;

        }
        $transport = Transport::where('code', $this->transportista['code'])->first();
        return $transport;

    }
    public function getEmailAttribute() {

        return $this->data['direml'];

    }
    public function getSellerAttribute() {

        $user = User::where('role', 'VND')->where('docket', $this->data['vendedor']['code'])->first();
        return $user;

    }
    public function user() {

        return $this->belongsTo(User::class, 'seller_id', 'id');

    }
    public static function one($request, $userID) {

        $user = User::find($userID);
        $client = $user->client;
        if ($client) {

            $clientResource = new ClientResource($client);
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'elements'  => array(
                    $clientResource
                )
            );

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Cliente no válido'
        );

    }
    public static function gets($request) {

        set_time_limit(600);
        if ($request->has('admin')) {

            $clients = self::where('id', '!=', '');
            if ($request->has('search')) {

                $clients = self::where('nrocta', 'LIKE', '%'.$request->get('search').'%')
                    ->orWhere('data->direml', 'LIKE', '%'.$request->get('search').'%')
                    ->orWhere('data->nrodoc', 'LIKE', '%'.$request->get('search').'%')
                    ->orWhere('data->respon', 'LIKE', '%'.$request->get('search').'%')
                    ->orWhere('data->telefn', 'LIKE', '%'.$request->get('search').'%')
                ;

            }
            $paginate = $request->has('paginate') ? (int) $request->get('paginate') : 10;
            $page = $request->has('page') ? (int) $request->get('page') : 1;
            $total = $clients->count();
            $totalPages = ceil($total / $paginate);
            $clientResource = ClientResource::collection(
                $clients->
                    orderBy('nrocta', 'ASC')->
                    paginate($paginate)
            );
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'page'      => $page,
                'total'     => array(
                    'clients'   => $total,
                    'pages'     => $totalPages
                ),
                'elements'  => $clientResource
            );
            
        } else {

            $clientResource = ClientResource::collection(
                self::where('id', '!=', '')->get()
            );
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'elements'  => $clientResource
            );

        }

    }
    /* ================== */
    public static function create($attr) {

        $model = self::where('nrocta', $attr['nrocta'])->first();
        if (!$model) {
            $model = new self;
            $model->nrocta = $attr['nrocta'];
        }
        if (isset($attr['user_id'])) {

            $model->user_id = $attr['user_id'];

        }
        $data = array();
        if (isset($attr['data'])) {

            if (isset($attr['data']['respon'])) {

                $data['respon'] = $attr['data']['respon'];

            }
            if (isset($attr['data']['usrvtmcl'])) {

                $data['usrvtmcl'] = $attr['data']['usrvtmcl'];

            }
            if (isset($attr['data']['usrvt_001'])) {

                $data['usrvt_001'] = $attr['data']['usrvt_001'];

            }
            if (isset($attr['data']['usrvt_002'])) {

                $data['usrvt_002'] = $attr['data']['usrvt_002'];

            }
            if (isset($attr['data']['usrvt_004'])) {

                $data['usrvt_004'] = $attr['data']['usrvt_004'];

            }
            if (isset($attr['data']['direccion'])) {

                $data['address'] = array(
                    'direccion' => $attr['data']['direccion'],
                    'codpos'    => $attr['data']['codpos'],
                    'localidad' => $attr['data']['descrp'],
                    'provincia' => $attr['data']['descr_001']
                );

            }
            if (isset($attr['data']['telefn'])) {

                $data['telefn'] = $attr['data']['telefn'];

            }
            if (isset($attr['data']['nrofax'])) {

                $data['nrofax'] = $attr['data']['nrofax'];

            }
            if (isset($attr['data']['direml'])) {

                $data['direml'] = $attr['data']['direml'];

            }
            if (isset($attr['data']['nrodoc'])) {

                $data['nrodoc'] = $attr['data']['nrodoc'];

            }
            if (isset($attr['data']['usrvt_003'])) {

                $data['usrvt_003'] = $attr['data']['usrvt_003'];

            }
            if (isset($attr['data']['vnddor'])) {

                $data['vendedor'] = array(
                    'code'      => $attr['data']['vnddor'],
                    'nombre'    => $attr['data']['descr_003'], 
                    'telefono'  => $attr['data']['nrotel'],
                    'email'     => $attr['data']['camail']
                );

            }
            if (isset($attr['data']['transportista'])) {

                $transport = Transport::where('code', $attr['data']['transportista'])->first();
                if ($transport) {

                    $model->transport_id = $transport->id;

                }
                $data['transportista'] = array(
                    'code'      => $attr['data']['transportista'],
                    'nombre'    => $attr['data']['descr_002']
                );

            }
            if (isset($attr['data']['whatsapp'])) {

                $data['whatsapp'] = $attr['data']['whatsapp'];

            }
            if (isset($attr['data']['instagram'])) {

                $data['instagram'] = $attr['data']['instagram'];

            }

        }
        $model->data = $data;
        $model->save();
        return $model;

    }
    public static function updateCollection(Bool $fromCron = false) {

        set_time_limit(0);
        $model = new self;
        $properties = array(
            'respon',
            'usrvtmcl',
            'usrvt_001',
            'usrvt_002',
            'usrvt_003',
            'direccion',
            'codpos',
            'descrp',
            'descr_001',
            'telefn',
            'nrofax',
            'direml',
            'nrodoc',
            'descr_002',
            'usrvt_004',
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
        );
        $errors = [];
        $users = [];
        $source = implode('/', [configs("FOLDER"), config('app.files.folder'), configs("FILE_CLIENTS", config('app.files.clients'))]);
        if (file_exists($source)) {

            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Cuenta') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) {

                    continue;

                }
                $nroCta = array_shift($elements);
                $data = array_combine($properties, $elements);
                $dataClient = array(
                    'nrocta'    => $nroCta,
                    'data'      => $data
                );
                unset($dataClient['NO']);
                $dataUser = array_combine(
                    ['uid', 'docket', 'name', 'username', 'phone', 'email', 'role', 'password'],
                    [NULL, $nroCta, $dataClient['data']['respon'], $dataClient['data']['nrodoc'], $dataClient['data']['telefn'], $dataClient['data']['direml'], 'USR', $dataClient['data']['nrodoc']]
                );
                $user = User::create($dataUser);
                $dataClient['user_id'] = $user->id;
                self::create($dataClient);
                $users[] = $user->id;

            }
            if (!empty($users)) {

                User::usr()->whereNotIn("id", $users)->delete();

            }
            fclose($file);
            if ($fromCron) {

                return responseReturn(true, 'Clientes insertados: '.self::count().' / Errores: '.count($errors));

            }
            return responseReturn(false, 'Clientes insertados: '.self::count().' / Errores: '.count($errors));

        }
        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }
        return responseReturn(true, 'Archivo no encontrado', 1, 400);

    }
    public static function action($request, $userID, $action) {

        $user = User::find($userID);
        $client = $user->client;
        if ($client) {

            $clientResource = new ClientResource($client);
            $xml = null;
            if ($action == 'analisis-deuda') {

                $xml = $client->analisis_deuda;
                if ($xml) {

                    $thead = '<thead>' .
                        '<tr>' .
                            '<th>COD. APLICACIÓN</th>' .
                            '<th>NRO. APLICACIÓN</th>' .
                            '<th>CÓDIGO</th>' .
                            '<th>NÚMERO</th>' .
                            '<th>CUOTA</th>' .
                            '<th>IMPORTE</th>' .
                            '<th>VENCIMIENTO</th>' .
                            '<th>EMISIÓN</th>' .
                            '<th>COMPROBANTE</th>' .
                            '<th>-</th>' .
                        '</tr>' .
                    '</thead>';
                    $total = collect($xml['Row'])->sum('Importe');
                    if (isset($xml['Row'][0])) {

                        $tr = collect($xml['Row'])->map(function($item) {

                            $item['pdf'] = '';
                            if (!empty($item['codigo'])) {

                                $name = "VT{$item['codigo']}{$item['numero']}.PDF";
                                $item['pdf'] = "<a target='_blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";

                            }
                            $item['Importe'] = floatval($item['Importe']);
                            $filtered = collect($item)->except(['Cod_cliente', 'Cliente', 'Vendedor']);
                            $tr = '<tr>' .
                                '<td>'.$filtered['cod_aplicacion'].'</td>' .
                                '<td>'.$filtered['nro_aplicacion'].'</td>' .
                                '<td>'.$filtered['codigo'].'</td>' .
                                '<td style="text-align: center;">'.$filtered['numero'].'</td>' .
                                '<td style="text-align: center;">'.$filtered['cuota'].'</td>' .
                                '<td style="text-align: right; white-space: nowrap; color: '.($filtered['Importe'] < 0 ? '#d50f25' : '#009622').'">'.($filtered['Importe'] < 0 ? '-$ '.number_format($filtered['Importe'] * -1, 2, ",", ".") : '$ '.number_format($filtered['Importe'], 2, ",", ".")).'</td>' .
                                '<td style="text-align: right;">'.$filtered['Vencimiento'].'</td>' .
                                '<td style="text-align: right;">'.$filtered['Emision'].'</td>' .
                                '<td>'.$filtered['Comprobante'].'</td>' .
                                '<td>'.$filtered['pdf'] ?? ''.'</td>' .
                            '</tr>';
                            return $tr;

                        })->join('');

                    } else {

                        $item = $xml['Row'];
                        $item['pdf'] = '';
                        if (!empty($item['codigo'])) {

                            $name = "VT{$item['codigo']}{$item['numero']}.PDF";
                            $item['pdf'] = "<a target='_blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";

                        }
                        $item['Importe'] = floatval($item['Importe']);
                        $filtered = collect($item)->except(['Cod_cliente', 'Cliente', 'Vendedor']);
                        $tr = '<tr>' .
                            '<td>'.(is_array($filtered['cod_aplicacion']) ? implode(', ', $filtered['cod_aplicacion']) : $filtered['cod_aplicacion']).'</td>' .
                            '<td>'.$filtered['nro_aplicacion'].'</td>' .
                            '<td>'.(is_array($filtered['codigo']) ? implode(', ', $filtered['codigo']) : $filtered['codigo']).'</td>' .
                            '<td style="text-align: center;">'.$filtered['numero'].'</td>' .
                            '<td style="text-align: center;">'.$filtered['cuota'].'</td>' .
                            '<td style="text-align: right; white-space: nowrap; color: '.($filtered['Importe'] < 0 ? '#d50f25' : '#009622').'">'.($filtered['Importe'] < 0 ? '-$ '.number_format($filtered['Importe'] * -1, 2, ",", ".") : '$ '.number_format($filtered['Importe'], 2, ",", ".")).'</td>' .
                            '<td style="text-align: right;">'.$filtered['Vencimiento'].'</td>' .
                            '<td style="text-align: right;">'.$filtered['Emision'].'</td>' .
                            '<td>'.(is_array($filtered['Comprobante']) ? implode(', ', $filtered['Comprobante']) : $filtered['Comprobante']).'</td>' .
                            '<td>'.$filtered['pdf'] ?? ''.'</td>' .
                        '</tr>';

                    }
                    return
                    array(
                        'error'     => false,
                        'status'    => 202,
                        'message'   => 'OK',
                        'thead'     => $thead,
                        'tbody'     => "<tbody>{$tr}</tbody>",
                        'total'     => $total < 0 ? '-$ '.number_format($total * -1, 2, ",", ".") : '$ '.number_format($total, 2, ",", "."),
                        'elements'  => array(
                            $clientResource
                        )
                    );

                }

            }
            if ($action == 'comprobantes') {

                $xml = $client->comprobantes;
                if ($xml) {

                    $thead = '<thead>' .
                        '<tr>' .
                            '<th>MÓDULO</th>' .
                            '<th>CÓDIGO</th>' .
                            '<th>NÚMERO</th>' .
                            '<th>EMISIÓN</th>' .
                            '<th>IMPORTE</th>' .
                        '</tr>' .
                    '</thead>';
                    $total = collect($xml['Row'])->sum('Importe');
                    $tr = collect($xml['Row'])->map(function($item) {

                        if (!isset($item['Modelo'])) {

                            return '';

                        }
                        $tr = '<tr>' .
                            '<td>'.$item['Modulo'].'</td>' .
                            '<td>'.$item['Codigo'].'</td>' .
                            '<td style="text-align: center;">'.$item['Numero'].'</td>' .
                            '<td style="text-align: right;">'.$item['Emision'].'</td>' .
                            '<td style="text-align: right; white-space: nowrap; color: '.($item['Importe'] < 0 ? '#d50f25' : '#009622').'">'.($item['Importe'] < 0 ? '-$ '.number_format($item['Importe'] * -1, 2, ",", ".") : '$ '.number_format($item['Importe'], 2, ",", ".")).'</td>' .
                        '</tr>';
                        return $tr;

                    })->join('');
                    if (!empty($tr)) {

                        return
                        array(
                            'error'     => false,
                            'status'    => 202,
                            'message'   => 'OK',
                            'thead'     => $thead,
                            'tbody'     => "<tbody>{$tr}</tbody>",
                            'total'     => $total < 0 ? '-$ '.number_format($total * -1, 2, ",", ".") : '$ '.number_format($total, 2, ",", "."),
                            'elements'  => array(
                                $clientResource
                            )
                        );

                    }

                }

            }
            if ($action == 'faltantes') {

                $xml = $client->faltantes;
                if ($xml) {

                    $thead = '<thead>' .
                        '<tr>' .
                            '<th>ARTÍCULO</th>' .
                            '<th>DESCRIPCIÓN</th>' .
                            '<th>FECHA</th>' .
                            '<th>PRECIO</th>' .
                            '<th>CANTIDAD</th>' .
                            '<th>TOTAL</th>' .
                            '<th>STOCK</th>' .
                        '</tr>' .
                    '</thead>';
                    if (isset($xml['Row']['ARTICULO'])) {

                        $tr = '<tr>' .
                            '<td>'.$xml['Row']['ARTICULO'].'</td>' .
                            '<td>'.$xml['Row']['DESCRIPCION'].'</td>' .
                            '<td style="text-align: right;">'.$xml['Row']['FECHA'].'</td>' .
                            '<td style="text-align: right; white-space: nowrap;">$ '.number_format($xml['Row']['PRECIO'], 2, ",", ".").'</td>' .
                            '<td style="text-align: center;">'.intval($xml['Row']['CANTIDAD']).'</td>' .
                            '<td style="text-align: right; white-space: nowrap;">$ '.number_format($xml['Row']['TOTAL'], 2, ",", ".").'</td>' .
                            '<td style="text-align: center;">'.intval($xml['Row']['STOCK_CENTRAL']).'</td>' .
                        '</tr>';

                    }
                    if (!isset($tr)) {

                        $tr = collect($xml['Row'])->sortByDesc('STOCK_CENTRAL')->map(function($item) {
    
                            if (
                                !isset($item['ARTICULO'])
                            ) {
    
                                return '';
    
                            }
                            $tr = '<tr>' .
                                '<td>'.$item['ARTICULO'].'</td>' .
                                '<td>'.$item['DESCRIPCION'].'</td>' .
                                '<td style="text-align: right;">'.$item['FECHA'].'</td>' .
                                '<td style="text-align: right; white-space: nowrap;">$ '.number_format($item['PRECIO'], 2, ",", ".").'</td>' .
                                '<td style="text-align: center;">'.intval($item['CANTIDAD']).'</td>' .
                                '<td style="text-align: right; white-space: nowrap;">$ '.number_format($item['TOTAL'], 2, ",", ".").'</td>' .
                                '<td style="text-align: center;">'.intval($item['STOCK_CENTRAL']).'</td>' .
                            '</tr>';
                            return $tr;
    
                        })->join('');

                    }
                    if (!empty($tr)) {

                        return
                        array(
                            'error'     => false,
                            'status'    => 202,
                            'message'   => 'OK',
                            'thead'     => $thead,
                            'tbody'     => "<tbody>{$tr}</tbody>",
                            'elements'  => array(
                                $clientResource
                            )
                        );

                    }

                }

            }
            return
            array(
                'error'     => true,
                'status'    => 404,
                'message'   => 'Elemento no encontrado'
            );

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Cliente no válido'
        );

    }
    public static function change($request) {

        $data = $request->all();
        $validator = Validator::make(
            $data,
            array(
                'update'    => 'required',
                'client'    => 'required',
                'data'      => 'required'
            )
        );
        if ($validator->fails()) {

            return array(
                'error'     => true,
                'status'    => 422,
                'message'   => 'Error en los datos enviados',
                'errors'    => $validator->errors()
            );

        }
        try {

            $user = User::find($request->client);
            $user->update(
                array(
                    'password' => \Hash::make($request->data['password'])
                )
            );
            $user->setConfig([
                'other' => ['secret' => $request->data['password']]
            ]);
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'Contraseña cambiada'
            );

        } catch (\Throwable $th) {

            return
            array(
                'error'     => true,
                'status'    => 500,
                'message'   => $th->getMessage()
            );

        }

    }
    public function getComprobantesAttribute() {

        $param = array( "pSPName" => "Consulta_Form", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {

            $client = new \nusoap_client('http://'.$this::msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', '' , '' , '', '');
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            if ($client->fault) {

                return false;

            } else {

                $err = $client->getError();
                if ($err) {

                    return false;

                } else {

                    $xml = simplexml_load_string(utf8_encode($result["EjecutarSP_XMLResult"]));
                    $string = json_encode($xml);
                    $array = json_decode($string, true);
                    return $array;

                }

            }

        } catch (\Throwable $th) {

            return false;

        }

    }
    public function getFaltantesAttribute() {

        $param = array( "pSPName" => "Consulta_Faltante", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {

            $client = new \nusoap_client('http://'.$this::msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', '' , '' , '', '');
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            
            if ($client->fault) {

                return false;

            } else {

                $err = $client->getError();

                if ($err) {

                    return false;

                } else {

                    $xml = simplexml_load_string(utf8_encode($result["EjecutarSP_XMLResult"]));
                    $string = json_encode($xml);
                    $array = json_decode($string, true);
                    return $array;

                }

            }
        } catch (\Throwable $th) {

            return false;

        }

    }
    public function getAnalisisDeudaAttribute() {

        $param = array( "pSPName" => "Consulta_CtaCte", 'pParamList' => '$Paramnrocta;' . $this->nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {

            $client = new \nusoap_client('http://'.$this::msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', '' , '' , '', '');
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            if ($client->fault) {

                return false;

            } else {

                $err = $client->getError();

                if ($err) {

                    return false;

                } else {

                    $xml = simplexml_load_string($result["EjecutarSP_XMLResult"]);
                    $string = json_encode($xml);
                    $array = json_decode($string, true);
                    return $array;

                }

            }

        } catch (\Throwable $th) {

            return false;

        }

    }

}
