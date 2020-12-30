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
    public $data;
    public function __construct() {
        $this->data = Ventor::first();
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
        $user = \Auth::user();
        $client = $user->getClient();
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
                $to = 'corzo.pabloariel@gmail.com';
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                Ticket::create([
                    'type' => 4,
                    'table' => 'users',
                    'table_id' => $user->id,
                    'obs' => '<p>Envio de mail con blanqueo de contraseña</p><p><strong>Tabla:</strong> emails / <strong>ID:</strong> ' . $email->id . '</p>',
                    'user_id' => \Auth::user()->id
                ]);
                Mail::to($to)
                    ->send(
                        new BaseMail(
                            $subject,
                            'Su contraseña se modificó correctamente.',
                            $html)
                    );
                return response()->json([
                    "error" => 0,
                    "mssg" => "Contraseña modificada."
                ], 200);
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
                $html .= "<p>Datos</p>";
                if (!empty($request->respon) && $client->respon != $request->respon)
                    $html .= "<p>Se modificará responsable <strong>de</strong> {$client->respon} <strong>a</strong> {$request->respon}</p>";
                
                if (!empty($request->telefn) && $client->telefn != $request->telefn)
                    $html .= "<p>Se modificará teléfono <strong>de</strong> {$client->telefn} <strong>a</strong> {$request->telefn}</p>";

                if (!empty($request->direml) && $client->direml != $request->direml)
                    $html .= "<p>Se modificará email <strong>de</strong> {$client->direml} <strong>a</strong> {$request->direml}</p>";
                if (!empty($request->obs))
                    $html .= "<p><strong>Observaciones:</strong> {$request->obs}</p>";
                $subject = 'Solicitó modificar información de su cuenta';
                $to = 'corzo.pabloariel@gmail.com';
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                Mail::to($to)
                    ->send(
                        new BaseMail(
                            $subject,
                            'La siguiente información será modificada.',
                            $html)
                    );
                /////////// A Ventor
                $html = "";
                $html .= "<p>Datos</p>";
                if (!empty($request->respon) && $client->respon != $request->respon)
                    $html .= "<p>Modificar [responsable] <strong>de</strong> {$client->respon} <strong>a</strong> {$request->respon}</p>";
                
                if (!empty($request->telefn) && $client->telefn != $request->telefn)
                    $html .= "<p>Modificar [teléfono] <strong>de</strong> {$client->telefn} <strong>a</strong> {$request->telefn}</p>";

                if (!empty($request->direml) && $client->direml != $request->direml)
                    $html .= "<p>Modificar [email] <strong>de</strong> {$client->direml} <strong>a</strong> {$request->direml}</p>";
                if (!empty($request->obs))
                    $html .= "<p><strong>Observaciones:</strong> {$request->obs}</p>";
                $subject = 'Modificar información de la cuenta #' . $client->nrocta;
                $to = 'corzo.pabloariel@gmail.com';
                $email = Email::create([
                    'use' => 0,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                Mail::to($to)
                    ->send(
                        new BaseMail(
                            $subject,
                            'El cliente solicitó modificar la siguiente información.',
                            $html)
                    );
                return response()->json([
                    "error" => 0,
                    "mssg" => "Datos enviados."
                ], 200);
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
                $html .= "<p>Datos</p>";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Email:</strong> {$request->email}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió un mensaje desde la página';
                $to = 'corzo.pabloariel@gmail.com';
                //if (!empty($request->mandar))
                    //$to = $request->mandar;
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                Mail::to($to)
                    ->send(
                        new BaseMail(
                            $subject,
                            'Contacto desde la página.',
                            $html,
                            ["name" => $request->nombre, "email" => $request->email])
                    );
                return response()->json([
                    "error" => 0,
                    "mssg" => "Consulta enviada."
                ], 200);
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
                $html .= "<p>Datos</p>";
                $html .= "<p><strong>Nombre:</strong> {$request->nombre}</p>";
                $html .= "<p><strong>Email:</strong> {$request->email}</p>";
                $html .= "<p><strong>Teléfono:</strong> {$request->telefono}</p>";
                $html .= "<p><strong>Localidad:</strong> {$request->localidad}</p>";
                $html .= "<p><strong>Mensaje:</strong> {$request->mensaje}</p>";
                $subject = 'Recibió una consulta desde la página';
                $to = 'corzo.pabloariel@gmail.com';
                $email = Email::create([
                    'use' => 1,
                    'subject' => $subject,
                    'body' => $html,
                    'from' => env('MAIL_BASE'),
                    'to' => $to
                ]);
                Mail::to($to)
                    ->send(
                        new BaseMail(
                            $subject,
                            'Consulta general desde la página.',
                            $html,
                            ["name" => $request->nombre, "email" => $request->email])
                    );
                return response()->json([
                    "error" => 0,
                    "mssg" => "Consulta enviada."
                ], 200);
                break;
        }
    }
}
