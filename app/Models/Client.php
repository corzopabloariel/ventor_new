<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;
use App\Models\User;
use App\Models\Ventor\Ticket;

class Client extends Model
{
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

    public function getName() {
        return 'clients';
    }
    public function user() {

        return $this->belongsTo('App\Models\User','user_id','id');

    }
    public function transport() {

        return $this->belongsTo('App\Models\Transport','transport_id','id');

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

    public static function one(String $_id, String $attr = "_id")
    {
        return self::where($attr, $_id)->first();
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

    public function changePassword(Request $request)  {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if($validator->fails()){

            return responseReturn(false, 'Contraseña necesaria', 1, 401);

        }
        $user = $this->user();
        $user->fill(["password" => \Hash::make($request->password)]);
        $user->save();

        Ticket::add(3, $user->id, 'users', 'Cambio de contraseña', [null, null, null], true, true);

        $user->setConfig([
            'other' => ['secret' => $request->password]
        ]);

        // Enviar mail
        if ($request->has("notice")) {
            $html = "";
            $html .= "<p>Datos de su cuenta</p>";
            $html .= "<p><strong>Usuario:</strong> {$user->username}</p>";
            $html .= "<p><strong>Contraseña:</strong> {$request->password}</p>";
            $subject = 'Se restableció su contraseña';
            $to = $user->email;
            if (config('app.env') == 'local') {
                $to = config('app.mails.to');
            }
            $email = Email::create([
                'use' => 0,
                'subject' => $subject,
                'body' => $html,
                'from' => config('app.mails.base'),
                'to' => $to
            ]);
            Ticket::add(4, $user->id, 'users', 'Envio de mail con blanqueo de contraseña<br/><strong>Tabla:</strong> emails / <strong>ID:</strong> ' . $email->id, [null, null, null], true, true);
            try {
                if (Email::sendPHPMailer($to, 'La contraseña se modificó a pedido de uds.', $subject, $html)) {
                    $email->fill(['sent' => 1]);
                    $email->save();
                } else {
                    $email->fill(['sent' => 0]);
                    $email->save();
                }
            } catch (\Throwable $th) {
                $email->fill(['error' => 1]);
                $email->save();
            }

            if ($email->sent == 1 && $email->error == 0) {

                return responseReturn(false, 'Se le notificó del blanqueo de contraseña al cliente '.$this->razon_social);

            } else {

                return responseReturn(false, 'Ocurrió un error en el envió del mail. Se modificó la contraseña del cliente '.$this->razon_social);

            }
        }

        return responseReturn(false, 'Contraseña blanqueada del cliente '.$this->razon_social);

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

            //self::removeAll();
            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Cuenta') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) continue;
                //try {
                    $nroCta = array_shift($elements);
                    $data = array_combine($properties, $elements);
                    $dataClient = array(
                        'nrocta'    => $nroCta,
                        'data'      => $data
                    );
                    unset($dataClient['NO']);
                    $user = User::usr()->withTrashed()->where('username', $dataClient['data']['nrodoc'])->first();
                    $dataUser = array_combine(
                        ['uid', 'docket', 'name', 'username', 'phone', 'email', 'role', 'password'],
                        [NULL, $nroCta, $dataClient['data']['respon'], $dataClient['data']['nrodoc'], $dataClient['data']['telefn'], $dataClient['data']['direml'], 'USR', $dataClient['data']['nrodoc']]
                    );
                    if ($user) {
                        //User::history($dataUser, $user->id);
                        $dataUser['deleted_at'] = null;
                        $dataUser['password'] = $user->password;
                        $user = User::mod($dataUser, $user);
                    } else {
                        $user = User::create($dataUser);
                    }
                    $dataClient['user_id'] = $user->id;
                    self::create($dataClient);
                    $users[] = $user->id;

                /*} catch (\Throwable $th) {

                    $errors[] = $elements;

                }*/

            }
            if (!empty($users)) {
                User::removeAll($users, 0);
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
}
