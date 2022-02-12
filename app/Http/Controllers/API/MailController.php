<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Send;
use App\Models\User;
use App\Models\Ventor\Ventor;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCompleteResource;

class MailController extends Controller
{
    public $data, $form;
    public function __construct() {

        $this->data = Ventor::first();
        $this->form = $this->data->formPrint();

    }
    public function index(Request $request) {

        $user = null;
        if ($request->has('user_id')) {

            $user = User::find($request->user_id);

        }
        $data = json_decode($request->data, true);
        $type = $data['type'];
        unset($data['type']);
        $values = call_user_func_array("self::{$type}", [$data, $user]);
        if (!$values['error']) {

            return Send::email($values);

        }
        return $values;

    }
    public function order($data, $user) {

        if (empty($user)) {

            return array(
                'error'     => true,
                'status'    => 401,
                'message'   => 'Sin autorización'
            );

        }
        $order = Order::find($data['id']);
        $obs = isset($order->obs) ? $order->obs : "";
        $title = $order->title;
        $body = view('mail.order')->with(
            array(
                'data' => array(
                    "<&TEXTOS>{$obs}</&TEXTOS>",
                    "<&TRACOD>".$order->transport->code."|".$order->transport->description." ".$order->transport->description."</&TRACOD>"
                )
            )
        )->render();
        $message = 'Pedido enviado';
        $sendTo = explode(';', $data['emails']);
        if ($data['is_test']) {

            $message = 'Pedido de prueba enviado a: '.$user->email;
            $title = '[PRUEBA] '.$title;
            $sendTo = array(
                $user->email
            );

        }
        $file = $order->export();
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => config("app.mail.FROM_ADDRESS"),
            'is_test'   => $data['is_test'],
            'is_order'  => true,
            'order'     => $order,
            'attach'    => $file
        );

    }
    public function orderToClient($data, $user) {

        if (empty($user)) {

            return array(
                'error'     => true,
                'status'    => 401,
                'message'   => 'Sin autorización'
            );

        }
        $order = Order::find($data['id']);
        $title = $order->title;
        $userClient = $order->client;
        $sendTo = array(
            $userClient->email
        );
        $body = view('mail.base')->with(
            array(
                'welcome'   => 'Pedido #'.$order->id,
                'title'     => $order->title,
                'subject'   => $order->title,
                'reply'     => null,
                'body'      => view("mail.orderClient")->with(
                    new OrderCompleteResource($order)
                )->render()
            )
        )->render();
        $message = 'Email enviado al cliente';
        if ($data['is_test']) {

            $title = '[PRUEBA] '.$title;
            $title .= ' - '.$userClient->email;
            $message = 'Email de prueba enviado a: '.$user->email;
            $sendTo = array(
                $user->email
            );

        }
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => config("app.mail.FROM_ADDRESS"),
            'is_test'   => $data['is_test'],
            'is_order'  => true
        );

    }
    public function contact($data, $user = null) {

        $validator = Validator::make(
            $data,
            array(
                'nombre'    => 'required',
                'email'     => 'required|email',
                'mensaje'   => 'required',
                'mandar'    => 'nullable|email'
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
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(
                    array(
                        'secret' => $this->data->captcha['private'],
                        'response' => $data['token']
                    )
                )
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if (!$responseKeys["success"]) {

            return array(
                'error'     => true,
                'status'    => 422,
                'message'   => 'Recaptcha inválido'
            );

        }
        $sendTo = isset($this->form['contacto']) ? $this->form['contacto'] : array('ventor@ventor.com.ar');
        $is_test = true;
        $message = 'Consulta enviada correctamente';
        $html = "";
        $html .= "<p><strong>Nombre:</strong> {$data['nombre']}</p>";
        $html .= "<p><strong>Teléfono:</strong> {$data['telefono']}</p>";
        $html .= "<p><strong>Mensaje:</strong> {$data['mensaje']}</p>";
        $title = 'Recibió un "Mensaje" ['.$data['email'].']';
        if (!empty($data['mandar']) && config('app.env') != 'local') {

            $sendTo = array(
                $data['mandar']
            );
            $is_test = false;

        }
        if (!empty($data['mandar'])) {

            $title .= ' - '.$data['mandar'];

        }
        $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
        $hour = date("H");
        if ($hour >= 12 && $hour <= 18) {

            $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';

        } else if ($hour >= 19 && $hour <= 23) {

            $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';

        }
        $body = view('mail.base')->with([
            'subject' => $title,
            'title' => 'Contacto desde la página.',
            'body' => $html,
            'welcome' => $welcome,
            'reply' => array(
                'name' => $data['nombre'],
                'email' => $data['email']
            )
        ])->render();
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => $data['email'],
            'is_test'   => $is_test,
            'reply'     => array(
                'name' => $data['nombre'],
                'email' => $data['email']
            ),
            'is_order'  => false
        );

    }
    public function pagos($data, $user = null) {

        $validator = Validator::make(
            $data,
            array(
                'nrocliente' => 'required',
                'razon' => 'required',
                'fecha' => 'required',
                'importe' => 'required',
                'banco' => 'required',
                'sucursal' => 'required',
                'facturas' => 'required',
                'descuento' => 'nullable'
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
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(
                    array(
                        'secret' => $this->data->captcha['private'],
                        'response' => $data['token']
                    )
                )
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if (!$responseKeys["success"]) {

            return array(
                'error'     => true,
                'status'    => 422,
                'message'   => 'Recaptcha inválido'
            );

        }
        $sendTo = isset($this->form['pagos']) ? $this->form['pagos'] : array('ventor@ventor.com.ar');
        $is_test = true;
        $message = 'Informe de pago enviado';
        $html = "";
        $html .= "<p><strong>Nro de Cliente:</strong> {$data['nrocliente']}</p>";
        $html .= "<p><strong>Razón social:</strong> {$data['razon']}</p>";
        $html .= "<p><strong>Fecha:</strong> {$data['fecha']}</p>";
        $html .= "<p><strong>Importe:</strong> {$data['importe']}</p>";
        $html .= "<p><strong>Banco:</strong> {$data['banco']}</p>";
        $html .= "<p><strong>Sucursal:</strong> {$data['sucursal']}</p>";
        $html .= "<p><strong>Facturas:</strong> {$data['facturas']}</p>";
        $html .= "<p><strong>Descuento:</strong> {$data['descuento']}</p>";
        $html .= "<p><strong>Observaciones:</strong> {$data['observaciones']}</p>";
        $title = 'Recibió un "Informe de pago" [# '.$data['nrocliente'].']';
        if (config('app.env') != 'local') {

            $is_test = false;

        }
        $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
        $hour = date("H");
        if ($hour >= 12 && $hour <= 18) {

            $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';

        } else if ($hour >= 19 && $hour <= 23) {

            $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';

        }
        $body = view('mail.base')->with([
            'subject' => $title,
            'title' => 'Informe de pago desde la página.',
            'body' => $html,
            'welcome' => $welcome
        ])->render();
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => config("app.mail.FROM_ADDRESS"),
            'is_test'   => $is_test,
            'is_order'  => false
        );

    }
    public function transmision($data, $user = null) {

        $validator = Validator::make(
            $data,
            array(
                'nombre' => 'required',
                'domicilio' => 'nullable',
                'email' => 'required|email',
                'potencia' => 'required',
                'factor' => 'required',
                'poleaMotor' => 'required',
                'poleaConducida' => 'required',
                'centroMin' => 'required',
                'centroMax' => 'required',
                'mensaje' => 'nullable'
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
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(
                    array(
                        'secret' => $this->data->captcha['private'],
                        'response' => $data['token']
                    )
                )
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if (!$responseKeys["success"]) {

            return array(
                'error'     => true,
                'status'    => 422,
                'message'   => 'Recaptcha inválido'
            );

        }
        $sendTo = isset($this->form['transmision']) ? $this->form['transmision'] : array('ventor@ventor.com.ar');
        $is_test = true;
        $message = 'Análisis de transmisión enviado';
        $html = "";
        $html .= "<h3>DATOS BÁSICOS</h3>";
        $html .= "<p><strong>Nombre y apellido:</strong> {$data['nombre']}</p>";
        $html .= "<p><strong>Teléfono:</strong> {$data['telefono']}</p>";
        $html .= "<p><strong>Domicilio:</strong> {$data['domicilio']}</p>";
        $html .= "<hr/>";
        $html .= "<p><strong>Tipo de transmisión:</strong> {$data['transmision']}</p>";
        $html .= "<p><strong>Tipo de correa:</strong> {$data['correa']}</p>";
        $html .= "<hr/>";
        $html .= "<p><strong>Potencia HP:</strong> {$data['potencia']}</p>";
        $html .= "<p><strong>Factor de servicio:</strong> {$data['factor']}</p>";
        $html .= "<p><strong>RPM polea motor:</strong> {$data['poleaMotor']}</p>";
        $html .= "<p><strong>RPM polea conducida:</strong> {$data['poleaConducida']}</p>";
        $html .= "<p><strong>Entre centro Min. (mm):</strong> {$data['centroMin']}</p>";
        $html .= "<p><strong>Entre centro Max. (mm):</strong> {$data['centroMax']}</p>";
        $html .= "<p><strong>Tipo de perfil:</strong> {$data['perfil']}</p>";
        $html .= "<hr/>";
        $html .= "<p><strong>Mensaje:</strong> {$data['mensaje']}</p>";
        $title = 'Recibió un "Análisis de transmisión" ['.$data['email'].']';
        if (!empty($data['mandar']) && config('app.env') != 'local') {

            $sendTo = array(
                $data['mandar']
            );
            $is_test = false;

        }
        if (!empty($data['mandar'])) {

            $title .= ' - '.$data['mandar'];

        }
        $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
        $hour = date("H");
        if ($hour >= 12 && $hour <= 18) {

            $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';

        } else if ($hour >= 19 && $hour <= 23) {

            $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';

        }
        $body = view('mail.base')->with([
            'subject' => $title,
            'title' => 'Análisis de transmisión desde la página.',
            'body' => $html,
            'welcome' => $welcome
        ])->render();
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => config("app.mail.FROM_ADDRESS"),
            'is_test'   => $is_test,
            'is_order'  => false
        );

    }
    public function datos($data, $user = null) {

        $validator = Validator::make(
            $data,
            array(
                'responsable' => 'nullable',
                'razon'   => 'nullable',
                'documento'   => 'nullable',
                'telefono'    => 'nullable',
                'email'   => 'nullable|email',
                'observaciones'   => 'nullable'
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
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(
                    array(
                        'secret' => $this->data->captcha['private'],
                        'response' => $data['token']
                    )
                )
            ]
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        /*if (!$responseKeys["success"]) {

            return array(
                'error'     => true,
                'status'    => 422,
                'message'   => 'Recaptcha inválido'
            );

        }*/
        $client = $user->client;
        $sendTo = isset($this->form['datos']) ? $this->form['datos'] : array('ventor@ventor.com.ar');
        $is_test = true;
        $message = 'Información enviada correctamente';
        $html = "";
        if (!empty($data['documento']) && $client->data['nrodoc'] != $data['documento']) {

            $html .= "<p>Modificar [DOCUMENTO] <strong>de</strong> {$client->data['nrodoc']} <strong>a</strong> {$data['documento']}</p>";

        }
        if (!empty($data['razon']) && $client->data['respon'] != $data['razon']) {

            $html .= "<p>Modificar [RAZÓN SOCIAL] <strong>de</strong> {$client->data['respon']} <strong>a</strong> {$data['razon']}</p>";

        }
        if (!empty($data['documento']) && $client->data['nrodoc'] != $data['documento']) {

            $html .= "<p>Modificar [RESPONSABLE] <strong>de</strong> {$client->data['nrodoc']} <strong>a</strong> {$data['documento']}</p>";

        }
        if (!empty($data['responsable']) && $client->data['usrvt_001'] != $data['responsable']) {

            $html .= "<p>Modificar [RESPONSABLE] <strong>de</strong> {$client->data['usrvt_001']} <strong>a</strong> {$data['responsable']}</p>";

        }
        if (!empty($data['telefono']) && $client->data['telefn'] != $data['telefono']) {

            $html .= "<p>Modificar [TELÉFONO] <strong>de</strong> {$client->data['telefn']} <strong>a</strong> {$data['telefono']}</p>";

        }
        if (!empty($data['email']) && $client->data['direml'] != $data['email']) {

            $html .= "<p>Modificar [EMAIL] <strong>de</strong> {$client->data['direml']} <strong>a</strong> {$data['email']}</p>";

        }
        if (!empty($data['observaciones'])) {

            $html .= "<p><strong>Observaciones:</strong> {$data['observaciones']}</p>";

        }
        $title = 'Modificar información de la cuenta #'.$client->nrocta;
        if (config('app.env') != 'local') {

            $is_test = false;

        }
        if (!empty($data['mandar'])) {

            $title .= ' - '.$data['mandar'];

        }
        $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
        $hour = date("H");
        if ($hour >= 12 && $hour <= 18) {

            $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';

        } else if ($hour >= 19 && $hour <= 23) {

            $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';

        }
        $body = view('mail.base')->with([
            'subject' => $title,
            'title' => 'El cliente solicitó modificar la siguiente información.',
            'body' => $html,
            'welcome' => $welcome,
            'reply' => array(
                'name' => $client->data['respon'],
                'email' => $client->data['direml']
            )
        ])->render();
        return array(
            'error'     => false,
            'body'      => $body,
            'subject'   => $title,
            'send_by'   => 'mail',
            'message'   => $message,
            'to'        => $sendTo,
            'from'      => config("app.mail.FROM_ADDRESS"),
            'is_test'   => $is_test,
            'reply'     => array(
                'name' => $client->data['respon'],
                'email' => $client->data['direml']
            ),
            'is_order'  => false
        );

    }

}