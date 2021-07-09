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
    public $data, $form, $PHPmailer;
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
        $to = isset($this->form[$section]) ? $this->form[$section] : config('app.mails.to');
        $user = session()->has('accessADM') ? session()->get('accessADM') : \Auth::user();
        $to_user = empty($user->email) ? $to : $user->email;
        if (config('app.env') == 'local') {
            $to = config('app.mails.to');
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
                    return responseReturn(false, 'Las contraseñas deben ser iguales', 1);
                }
                $user->fill(["password" => \Hash::make($request->password)]);
                $user->save();
                Ticket::add(3, $user->id, 'users', 'Cambio de contraseña', [null, null, null], true, true);
                $html = "";
                $html .= "<p>Datos de su cuenta</p>";
                $html .= "<p style='padding-left: 30px;'><strong>Usuario:</strong> {$user->username}</p>";
                $html .= "<p style='padding-left: 30px;'><strong>Contraseña:</strong> {$request->password}</p>";
                $subject = 'Se restableció su contraseña';
                if (empty($user->email)) {
                    $subject .= " - SIN EMAIL";
                } else {
                    if (env('APP_ENV') == 'local')
                        $subject .= " - " . $user->email;
                }
                $response = Email::sendPHPMailer($to_user, 'Su contraseña se modificó correctamente.', $subject, $html);
                if (isset($response[3])) {
                    Ticket::add(4, $user->id, 'users', 'Envio de mail con blanqueo de contraseña<br/><strong>Tabla:</strong> emails / <strong>ID:</strong> ' . $response[3]->id, [null, null, null], true, true);
                }
                if ($response[0] == 202) {
                    return responseReturn(false, 'Contraseña modificada');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
                }
                break;
            case "datos":
                if (empty($request->respon) && empty($request->telefn) && empty($request->direml) && empty($request->obs)) {
                    return responseReturn(false, 'Complete alguna información del formulario', 1);
                }
                //////////// Al Cliente
                $html = "";
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
                    if (config('app.env') == 'local')
                        $subject .= " - " . $user->email;
                }
                $response = Email::sendPHPMailer($to_user, 'La siguiente información será modificada.', $subject, $html);
                /////////// A Ventor
                $html = "";
                if (!empty($request->respon) && $client->respon != $request->respon)
                    $html .= "<p>Modificar [RESPONSABLE] <strong>de</strong> {$client->respon} <strong>a</strong> {$request->respon}</p>";
                if (!empty($request->telefn) && $client->telefn != $request->telefn)
                    $html .= "<p>Modificar [TELÉFONO] <strong>de</strong> {$client->telefn} <strong>a</strong> {$request->telefn}</p>";
                if (!empty($request->direml) && $client->direml != $request->direml)
                    $html .= "<p>Modificar [EMAIL] <strong>de</strong> {$client->direml} <strong>a</strong> {$request->direml}</p>";
                if (!empty($request->obs))
                    $html .= "<p><strong>Observaciones:</strong> {$request->obs}</p>";
                $subject = 'Modificar información de la cuenta #' . $client->nrocta;
                $response2 = Email::sendPHPMailer($to, 'El cliente solicitó modificar la siguiente información.', $subject, $html);

                if ($response[0] == 202) {
                    return responseReturn(false, 'Datos enviados');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
                }
                break;
            case "contacto":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'email' => 'required',
                    'mensaje' => 'required'
                ]);
                if($validator->fails()){
                    return responseReturn(false, 'Faltan datos necesarios', 1);
                }

                $html = "";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió un "Mensaje" ['.$request->email.']';
                if (!empty($request->mandar) && config('app.env') != 'local')
                    $to = $request->mandar;
                else if (!empty($request->mandar))
                    $subject .= " - " . $request->mandar;
                $response = Email::sendPHPMailer($to_user, 'Contacto desde la página.', $subject, $html, ["name" => $request->nombre, "email" => $request->email], ['contacto']);

                if ($response[0] == 202) {
                    return responseReturn(false, 'Consulta enviada');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
                }
                break;
            case "consulta":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'email' => 'required',
                    'mensaje' => 'required'
                ]);
                if($validator->fails()){
                    return responseReturn(false, 'Faltan datos necesarios', 1);
                }
                $html = "";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Localidad:</strong> {$request->localidad}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió una "Consulta" ['.$request->email.']';
                $response = Email::sendPHPMailer($to_user, 'Consulta general desde la página.', $subject, $html, ["name" => $request->nombre, "email" => $request->email], ['consulta']);

                if ($response[0] == 202) {
                    return responseReturn(false, 'Consulta enviada');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
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
                    return responseReturn(false, 'Faltan datos necesarios', 1);
                }
                $html = "";
                $html .= "<p><strong>Nro de Cliente:</strong> {$request->nrocliente}</p>";
                $html .= "<p><strong>Razón social:</strong> {$request->razon}</p>";
                $html .= "<p><strong>Fecha:</strong> {$request->fecha}</p>";
                $html .= "<p><strong>Importe:</strong> {$request->importe}</p>";
                $html .= "<p><strong>Banco:</strong> {$request->banco}</p>";
                $html .= "<p><strong>Sucursal:</strong> {$request->sucursal}</p>";
                $html .= "<p><strong>Facturas:</strong> {$request->facturas}</p>";
                $html .= "<p><strong>Descuento:</strong> {$request->descuento}</p>";
                $html .= "<p><strong>Observaciones:</strong> {$request->observaciones}</p>";
                $subject = 'Recibió un "Informe de pago" [# '.$request->nrocliente.']';

                $response = Email::sendPHPMailer($to, 'Informe de pago desde la página.', $subject, $html, null, ['pago']);

                if ($response[0] == 202) {
                    return responseReturn(false, 'Informe de pago enviado');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
                }
                break;
            case "transmision":
                $validator = Validator::make($request->all(), [
                    'nombre' => 'required',
                    'domicilio' => 'required',
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
                    return responseReturn(false, 'Faltan datos necesarios', 1);
                }
                $html = "";
                $html .= "<h3>DATOS BÁSICOS</h3>";
                $html .= "<p style='padding-left: 30px;'><strong>Nombre y apellido:</strong> {$request->nombre}</p>";
                $html .= "<p style='padding-left: 30px;'><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p style='padding-left: 30px;'><strong>Domicilio:</strong> {$request->domicilio}</p>";
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
                $html .= "<p><strong>Tipo de perfil:</strong> {$request->perfil}</p>";
                $html .= "<hr/>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió un "Análisis de transmisión" ['.$request->email.']';
                $response = Email::sendPHPMailer($to, 'Análisis de transmisión desde la página.', $subject, $html, ['name' => $request->nombre, 'email' => $request->email], ['transmisión']);
                
                if ($response[0] == 202) {
                    return responseReturn(false, 'Análisis de transmisión enviado');
                } else {
                    return responseReturn(false, 'Ocurrió un error', 1);
                }
                break;
        }
    }
}
