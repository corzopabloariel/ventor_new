<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Exports\OrderExport;
use App\Mail\BaseMail;
use App\Mail\OrderMail;
use Excel;

class Email extends Model
{
    use HasFactory;
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


    /* ================== */
    public static function create($attr)
    {
        $mongo = EmailMongo::create($attr);
        $model = new self;
        $model->type = 'SMTP';
        $model->uid = $mongo->_id;
        $model->use = $attr['use'];
        $model->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $model->ip = self::getIp();
        $model->save();

        return $model;
    }

    private static function getIp() {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function sendOrder($title, $message, $order) {
        $toArray = [config('app.mails.to')];
        if (true) {
            $toArray[] = 'sebastianevillarreal@gmail.com';
            $toArray[] = 'pedidos@ventor.com.ar';
            //$toArray[] = 'corzo.pabloariel@gmail.com';
            if (!$order->is_test) {
                $toArray[] = 'pedidos.ventor@gmx.com';
                $toArray = array_reverse($toArray);
            }
        }
        $to = array_shift($toArray);

        $email = self::create([
            'use' => 0,
            'subject' => $title,
            'body' => $message,// Guarda Array del mensaje
            'from' => config('app.mails.base'),
            'to' => $to,
            'is_order' => true
        ]);
        try {
            Mail::to($to)
                ->bcc($toArray)
                ->send(
                    new OrderMail(
                        $message,
                        $title,
                        Excel::download(new OrderExport($order->_id), 'PEDIDO.xls')->getFile(), ['as' => 'PEDIDO.xls']
                    )
                );
            $email->fill(['sent' => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(['error' => 1]);
            $email->save();
        }
        return $email;
    }

    /**
     * Order = Pedido nuevo del cliente
     * userControler = Usuario admin logueado como cliente
     */
    public static function sendClient($order, $userControl = null) {
        if (isset($order->client['direml']) && isset($order->is_test) && !$order->is_test && empty($userControl) || !str_contains($order->title, 'PRUEBA')) {
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
        $email = self::create([
            'use' => 0,
            'subject' => $subject,
            'body' => $html,
            'from' => config('app.mails.base'),
            'to' => $to
        ]);
        try {
            Mail::to($to)
                ->send(
                    new BaseMail(
                        $subject,
                        'Lista de productos.',
                        $html)
                );
            $email->fill(["sent" => 1]);
            $email->save();
        } catch (\Throwable $th) {
            $email->fill(["error" => 1]);
            $email->save();
        }
        return $email;
    }
}
