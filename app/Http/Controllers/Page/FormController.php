<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\BaseMail;
use App\Models\Ventor\Ventor;
use App\Models\Ventor\Ticket;
use App\Models\Email;

class FormController extends Controller
{
    public $data, $form;
    public function __construct() {
        $this->data = Ventor::first();
        $this->form = $this->data->formPrint();
    }

    public function client(Request $request, String $section)
    {
        $captcha = $request->token;
        if (!$captcha) {
            return json_encode(["error" => 0 , "mssg" => "Captcha no verificado"]);
            exit;
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => $this->data->captcha['private'], 'response' => $captcha);
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if (!$responseKeys["success"]) {
            return json_encode(["error" => 0 , "mssg" => "Ocurrió un error"]);
            exit;
        }
        $to = isset($this->form[$section]) ? $this->form[$section] : env('MAIL_TO');
        $user = session()->has('accessADM') ? session()->get('accessADM') : \Auth::user();
        $to_user = empty($user->email) ? $to : $user->email;
        if (env('APP_ENV') == 'local') {
            $to = env('MAIL_TO');
            $to_user = $to;
        }
        $client = $user ? $user->getClient() : null;
        switch($section) {
            case "password":
                $validator = Validator::make($request->all(), [
                    'password' => 'required',
                    'password_2' => 'required|same:password'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Las contraseñas deben ser iguales."
                    ], 200);
                }
                $user->fill(["password" => \Hash::make($request->password)]);
                $user->save();

                Ticket::create([
                    'type' => 3,
                    'table' => 'users',
                    'table_id' => $user->id,
                    'obs' => '<p>Cambio de contraseña</p>',
                    'user_id' => \Auth::user()->id
                ]);
                $html = "";
                $html .= "<p>Datos de su cuenta</p>";
                $html .= "<p><strong>Usuario:</strong> {$user->username}</p>";
                $html .= "<p><strong>Contraseña:</strong> {$request->password}</p>";
                $subject = 'Se restableció su contraseña';
                if (empty($user->email)) {
                    $subject .= " - SIN EMAIL";
                } else {
                    if (env('APP_ENV') == 'local')
                        $subject .= " - " . $user->email;
                }
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to_user
                ]);
                Ticket::create([
                    'type' => 4,
                    'table' => 'users',
                    'table_id' => $user->id,
                    'obs' => '<p>Envio de mail con blanqueo de contraseña</p><p><strong>Tabla:</strong> emails / <strong>ID:</strong> ' . $email->id . '</p>',
                    'user_id' => \Auth::user()->id
                ]);
                try {
                    Mail::to($to_user)
                        ->send(
                            new BaseMail(
                                $subject,
                                'Su contraseña se modificó correctamente.',
                                $html)
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Contraseña modificada."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
            case "datos":
                if (empty($request->respon) && empty($request->telefn) && empty($request->direml) && empty($request->obs)) {
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Complete alguna información del formulario"
                    ], 200);
                }
                //////////// Al Cliente
                $html = "";
                $html .= "<h3>Datos</h3>";
                if (!empty($request->respon) && $client->respon != $request->respon)
                    $html .= "<p>Se modificará responsable <strong>de</strong> {$client->respon} <strong>a</strong> {$request->respon}</p>";
                
                if (!empty($request->telefn) && $client->telefn != $request->telefn)
                    $html .= "<p>Se modificará teléfono <strong>de</strong> {$client->telefn} <strong>a</strong> {$request->telefn}</p>";

                if (!empty($request->direml) && $client->direml != $request->direml)
                    $html .= "<p>Se modificará email <strong>de</strong> {$client->direml} <strong>a</strong> {$request->direml}</p>";
                if (!empty($request->obs))
                    $html .= "<p><strong>Observaciones:</strong> {$request->obs}</p>";
                $subject = 'Solicitó modificar información de su cuenta';
                if (empty($user->email)) {
                    $subject .= " - SIN EMAIL";
                } else {
                    if (env('APP_ENV') == 'local')
                        $subject .= " - " . $user->email;
                }
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to_user
                ]);
                try {
                    Mail::to($to_user)
                        ->send(
                            new BaseMail(
                                $subject,
                                'La siguiente información será modificada.',
                                $html)
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();
                }
                /////////// A Ventor
                $html = "";
                $html .= "<h3>Datos</h3>";
                if (!empty($request->respon) && $client->respon != $request->respon)
                    $html .= "<p>Modificar [responsable] <strong>de</strong> {$client->respon} <strong>a</strong> {$request->respon}</p>";
                
                if (!empty($request->telefn) && $client->telefn != $request->telefn)
                    $html .= "<p>Modificar [teléfono] <strong>de</strong> {$client->telefn} <strong>a</strong> {$request->telefn}</p>";

                if (!empty($request->direml) && $client->direml != $request->direml)
                    $html .= "<p>Modificar [email] <strong>de</strong> {$client->direml} <strong>a</strong> {$request->direml}</p>";
                if (!empty($request->obs))
                    $html .= "<p><strong>Observaciones:</strong> {$request->obs}</p>";
                $subject = 'Modificar información de la cuenta #' . $client->nrocta;
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                try {
                    Mail::to($to)
                        ->send(
                            new BaseMail(
                                $subject,
                                'El cliente solicitó modificar la siguiente información.',
                                $html)
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Datos enviados."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
            case "contacto":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'email' => 'required',
                    'mensaje' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Faltan datos necesarios."
                    ], 200);
                }

                $html = "";
                $html .= "<h3>Datos</h3>";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Email:</strong> {$request->email}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió un mensaje desde la página';
                if (!empty($request->mandar) && env('APP_ENV') == 'production')
                    $to = $request->mandar;
                else if (!empty($request->mandar))
                    $subject .= " - " . $request->mandar;
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                try {
                    Mail::to($to)
                        ->send(
                            new BaseMail(
                                $subject,
                                'Contacto desde la página.',
                                $html,
                                ["name" => $request->nombre, "email" => $request->email])
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Consulta enviada."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
            case "consulta":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'email' => 'required',
                    'mensaje' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Faltan datos necesarios."
                    ], 200);
                }
                $html = "";
                $html .= "<h3>Datos</h3>";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Email:</strong> {$request->email}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Localidad:</strong> {$request->localidad}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió una consulta desde la página';
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                try {
                    Mail::to($to)
                        ->send(
                            new BaseMail(
                                $subject,
                                'Consulta general desde la página.',
                                $html,
                                ["name" => $request->nombre, "email" => $request->email])
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Consulta enviada."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
            case "pagos":
                $validator = Validator::make($request->all(), [
                    'nrocliente' => 'required',
                    'razon' => 'required',
                    'fecha' => 'required',
                    'importe' => 'required',
                    'banco' => 'required',
                    'sucursal' => 'required',
                    'facturas' => 'required',
                    'descuento' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Faltan datos necesarios."
                    ], 200);
                }
                $html = "";
                $html .= "<h3>Datos</h3>";
                $html .= "<p><strong>Nro de Cliente:</strong> {$request->nrocliente}</p>";
                $html .= "<p><strong>Razón social:</strong> {$request->razon}</p>";
                $html .= "<p><strong>Fecha:</strong> {$request->fecha}</p>";
                $html .= "<p><strong>Importe:</strong> {$request->importe}</p>";
                $html .= "<p><strong>Banco:</strong> {$request->banco}</p>";
                $html .= "<p><strong>Sucursal:</strong> {$request->sucursal}</p>";
                $html .= "<p><strong>Facturas:</strong> {$request->facturas}</p>";
                $html .= "<p><strong>Descuento:</strong> {$request->descuento}</p>";
                $html .= "<p><strong>Observaciones:</strong> {$request->observaciones}</p>";
                $subject = 'Recibió un informe de pago desde la página';
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                try {
                    Mail::to($to)
                        ->send(
                            new BaseMail(
                                $subject,
                                'Informe de pago desde la página.',
                                $html)
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Informe de pago enviado."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
            case "transmision":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'domicilio' => 'required',
                    'localidad' => 'required',
                    'email' => 'required',
                    'potencia' => 'required',
                    'factor' => 'required',
                    'poleaMotor' => 'required',
                    'poleaConducida' => 'required',
                    'centroMin' => 'required',
                    'centroMax' => 'required',
                    'mensaje' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => 1,
                        "mssg" => "Faltan datos necesarios."
                    ], 200);
                }
                $html = "";
                $html .= "<h3>Datos</h3>";
                $html .= "<p><strong>Nombre y apellido:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Domicilio:</strong> {$request->domicilio}</p>";
                $html .= "<p><strong>Localidad:</strong> {$request->localidad}</p>";
                $html .= "<p><strong>Email:</strong> {$request->email}</p>";
                $html .= "<hr/>";
                $html .= "<p><strong>Tipo de transmisión:</strong> {$request->transmision}</p>";
                $html .= "<p><strong>Tipo de correa:</strong> {$request->correa}</p>";
                $html .= "<hr/>";
                $html .= "<p><strong>Potencia HP:</strong> {$request->potencia}</p>";
                $html .= "<p><strong>Factor de servicio:</strong> {$request->factor}</p>";
                $html .= "<p><strong>RPM polea motor:</strong> {$request->poleaMotor}</p>";
                $html .= "<p><strong>RPM polea conducida:</strong> {$request->poleaConducida}</p>";
                $html .= "<p><strong>Entre centro Min. (mm):</strong> {$request->centroMin}</p>";
                $html .= "<p><strong>Entre centro Max. (mm):</strong> {$request->centroMax}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $html .= "<hr/>";
                $html .= "<p><strong>Tipo de perfil:</strong> {$request->perfil}</p>";
                $subject = 'Recibió una análisis de transmisión desde la página';
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                try {
                    Mail::to($to)
                        ->send(
                            new BaseMail(
                                $subject,
                                'Análisis de transmisión desde la página.',
                                $html,
                                ["name" => $request->nombre, "email" => $request->email])
                        );
                    $email->fill(["sent" => 1]);
                    $email->save();
                    return response()->json([
                        "error" => 0,
                        "mssg" => "Análisis de transmisión enviado."
                    ], 200);
                } catch (\Throwable $th) {
                    $email->fill(["error" => 1]);
                    $email->save();

                    return response()->json([
                        "error" => 1,
                        "mssg" => "Ocurrió un error."
                    ], 200);
                }
                break;
        }
    }
}
