<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Exports\OrderExport;
use App\Mail\BaseMail;
use App\Mail\OrderMail;
use SendGrid\Mail\Mail AS SendgridMail;
use Excel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email extends Model
{
    use HasFactory;
    use HybridRelations;

    protected $fillable = [
        'uid',
        'type',
        'sent',
        'error',
        'from',
        'ip',
        'user_agent'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $with = ['mongo'];

    /* ================== */
    public static function create($attr)
    {
        $mongo = EmailMongo::create($attr);
        $model = new self;
        $model->type = $attr['type'] ?? 'SMTP';
        $model->uid = $mongo->_id;
        $model->use = $attr['use'];
        $model->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '-';
        $model->ip = self::getIp();
        $model->save();

        return $model;
    }

    public static function sendSendgrid($to, $title, $subject, $html, $reply = null, $categories = null, $textPlain = null, $attach = null, $is_order = false) {

        $emailSendgrid = new SendgridMail();
        $emailSendgrid->setFrom(config('app.sendgrid.FROM_ADDRESS'), config('app.sendgrid.FROM_NAME'));
        $emailSendgrid->setSubject($subject);
        if (is_string($to))
            $emailSendgrid->addTo($to);
        else {
            $emails = collect($to)->mapWithKeys(function($item) {
                return [$item => $item];
            })->toArray();
            $emailSendgrid->addTos($emails);
        }
        if (empty($textPlain)) {
            $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
            $hour = date("H");
            if ($hour >= 12 && $hour <= 18)
                $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';
            else if ($hour >= 19 && $hour <= 23)
                $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';
            $body = view('mail.base')->with([
                'subject' => $subject,
                'title' => $title,
                'body' => $html,
                'welcome' => $welcome,
                'reply' => $reply
            ])->render();
            $emailSendgrid->addContent("text/html", $body);
        } else {
            $body = $textPlain;
            $emailSendgrid->addContent("text/html", $body);
        }
        if (!empty($reply)) {
            $emailSendgrid->setReplyTo($reply['email'], $reply['name']);
        }

        if (!empty($attach)) {
            $file_encoded = base64_encode(file_get_contents($attach['file']));
            $emailSendgrid->addAttachment(
                $file_encoded,
                mime_content_type($attach['file']),
                $attach['name'],
                'attachment'
            );
        }

        if ($categories) {
            $emailSendgrid->addCategories($categories);
            $serviceCategories = $categories;
        } else {
            $serviceCategories = array('Mail de web');
        }

        $service = array('type' => implode(',',$serviceCategories));
        $emailSendgrid->addCustomArg('service', json_encode($service));
        $sendgrid = new \SendGrid(config('app.sendgrid.API_KEY'));
        //////////////////
        $email = self::create([
            'use' => 0,
            'type' => 'API',
            'subject' => $subject,
            'body' => $body,
            'from' => config('app.mails.base'),
            'to' => $to,
            'is_order' => $is_order
        ]);
        //////////////////

        $resp = [];
        try {
            $response = $sendgrid->send($emailSendgrid);
            $resp[0] = $response->statusCode();
            foreach ($response->headers() as $header) {
                if (strpos($header, 'X-Message-Id') !== false) {
                    $resp[1] = $header;
                }
            }
            if($response->statusCode() != 202){
                $resp[1] = 'X-Message-Id:';
                $resp[2] = 'Revisar configuración Sendgrid';
                $email->fill(["error" => 1]);
            } else {
                $resp[2] = $response->body();
                $resp[3] = $email;
                $email->fill(["sent" => 1]);
            }
        } catch (Exception $e) {
            $resp[0] = 900;
            $resp[1] = 0;
            $resp[2] = $e->getMessage();
            $email->fill(["error" => 1]);
        }
        $email->save();

        if (!empty($attach) && isset($attach['delete']) && $attach['delete']) {
            if (file_exists($attach['file']))
                unlink($attach['file']);
        }
        return $resp;
    }

    public static function sendPHPMailer($to, $title, $subject, $html, $reply = null) {

        $welcome = 'Buen <strong style="font-weight:600;">día</strong>';
        $hour = date("H");
        if ($hour >= 12 && $hour <= 18)
            $welcome = 'Buenas <strong style="font-weight:600;">tardes</strong>';
        else if ($hour >= 19 && $hour <= 23)
            $welcome = 'Buenas <strong style="font-weight:600;">noches</strong>';
        $body = view('mail.base')->with([
            'subject' => $subject,
            'title' => $title,
            'body' => $html,
            'welcome' => $welcome,
            'reply' => $reply
        ])->render();
        //////////////////
        $email = self::create([
            'use' => 0,
            'type' => 'SMTP',
            'subject' => $subject,
            'body' => $body,
            'from' => config('app.mails.base'),
            'to' => $to
        ]);
        //////////////////
        $PHPmailer = new PHPMailer;
        $PHPmailer->isSMTP();
        $PHPmailer->Host = config('app.mail.HOST');
        $PHPmailer->SMTPAuth = true;
        $PHPmailer->Username = config('app.mail.USERNAME');
        $PHPmailer->Password = config('app.mail.PASSWORD');
        $PHPmailer->Port = config('app.mail.PORT');
        //$PHPmailer->SMTPSecure = config('app.mail.ENCRYPTION');
        $PHPmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $PHPmailer->From = config('app.mail.FROM_ADDRESS');
        $PHPmailer->FromName = config('app.mail.FROM_NAME');
        $PHPmailer->isHTML(true);
        $PHPmailer->CharSet = "UTF-8";
        if (!empty($reply)) {
            $PHPmailer->addReplyTo($reply['email'], $reply['name']);
        }

        $PHPmailer->addAddress($to);
        $PHPmailer->Subject = $subject;
        $PHPmailer->Body = $body;

        $resp = [];
        $resp[0] = 0;
        $resp[1] = 0;
        $resp[2] = 0;
        try {
            if ($PHPmailer->send()) {
                $resp[0] = 202;
                $resp[3] = $email;
                $email->fill(["sent" => 1]);
            } else {
                $resp[0] = 200;
                $email->fill(["error" => 1]);
            }
        } catch (Exception $e) {
            $resp[0] = 900;
            $resp[1] = 0;
            $resp[2] = $e->getMessage();
            $email->fill(["error" => 1]);
        }
        $email->save();
        if ($resp[0] != 202) {
            \DB::table('errors')->insert([
                'host' => 'email.ventor.com.ar',
                'description' => $email->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            $resp[0] = 202;// Notifico igual que está todo OK
        }
        return $resp;
    }

    private static function getIp() {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function sendOrder($title, $message, $order) {
        $emails = configs('EMAILS_ORDER');
        if ($order->is_test && config('app.env') != 'local') {
            // Quito el GMX
            $emails = str_replace('pedidos.ventor@gmx.com;', '', $emails);
        }
        $emails = explode(';', $emails);
        $message = view('mail.order')->with([
            'mensaje' => $message
        ])->render();
        Excel::store(new OrderExport($order->_id), 'pedido-'.$order->_id.'.xls', 'local');
        $attach = [
            'file' => storage_path('app').'/pedido-'.$order->_id.'.xls',
            'name' => 'PEDIDO.xls',
            'mime' => 'application/vnd.ms-excel',
            'delete' => env('DELETE_ORDER')
        ];
        $response = self::sendSendgrid($emails, $title, $title, null, null, ['pedido'], $message, $attach, true);
        return $response[3] ?? null;
    }

    /**
     * Order = Pedido nuevo del cliente
     * userControler = Usuario admin logueado como cliente
     */
    public static function sendClient($order, $userControl = null) {
        if (isset($order->client['direml']) && config('app.env') != 'local' && isset($order->is_test) && !$order->is_test && empty($userControl) || !str_contains($order->title, 'PRUEBA')) {
            $to = $order->client['direml'];
            $subject = $order->title;
        } else {
            $to = \Auth::user()->email;
            $subject = $order->title;
            if (isset($order->client['direml'])) {
                $subject .=  ' - ' . $order->client['direml'];
            }
            if ($userControl)
                $subject .=  ' - Logueado como cliente #' . $userControl->docket;
        }
        
        $html = \View::make("mail.order_products", ["order" => $order])->render();
        $response = Email::sendPHPMailer($to, 'Lista de productos.', $subject, $html, null, ['pago']);
        return $response[3] ?? null;
    }

    public function mongo() {
        return $this->belongsTo(EmailMongo::class, 'uid', '_id');
    }
}
