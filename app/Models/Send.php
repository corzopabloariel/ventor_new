<?php

namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Database\Eloquent\Model;

use App\Mail\DemoMail;
use Illuminate\Support\Facades\Mail;

class Send extends Model
{
    protected $table = "emails";

    protected $fillable = [
        'uid',
        'type',
        'send_by',
        'from',
        'to',
        'subject',
        'body',
        'sent',
        'error',
        'use',
        'is_order',
        'ip',
        'user_agent'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'to'        => 'array',
        'sent'      => 'boolean',
        'error'     => 'boolean',
        'is_order'  => 'boolean'
    ];

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
    public static function create($attr) {

        $model = new self;
        $model->type = $attr['type'] ?? 'SMTP';
        $model->send_by = isset($attr['send_by']) ? $attr['send_by'] : NULL;
        $model->use = $attr['use'];
        $model->subject = $attr['subject'];
        $model->body = $attr['body'];
        $model->from = $attr['from'];
        $model->to = $attr['to'];
        $model->is_order = isset($attr['is_order']) ? $attr['is_order'] : false;
        $model->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '-';
        $model->ip = self::getIp();
        $model->save();
        return $model;

    }
    public static function email($data) {

        set_time_limit(600);
        $order = null;
        $email = self::create([
            'use'       => 0,
            'type'      => 'SMTP',
            'send_by'   => $data['send_by'],
            'subject'   => $data['subject'],
            'body'      => $data['body'],
            'from'      => $data['from'],
            'to'        => $data['to'],
            'is_order'  => $data['is_order']
        ]);
        if (isset($data['order'])) {

            $order = $data['order'];
            $order->update(
                array('email' => $data['to'])
            );

        }
        try {

            Mail::to($data['to'])->send(new DemoMail($data));
            $email->update(
                array(
                    'sent' => 1
                )
            );
            if ($order) {

                $order->update(
                    array('sent' => 1)
                );

            }
            if (isset($data['attach']) && isset($data['attach']['delete']) && $data['attach']['delete']) {

                if (file_exists($data['attach']['file'])) {

                    unlink($data['attach']['file']);

                }

            }
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => $data['message']
            );

        } catch (\Throwable $th) {

            $email->update(
                array(
                    'error' => 1
                )
            );
            return
            array(
                'error'     => true,
                'status'    => 400,
                'message'   => $th->getMessage()
            );

        }

    }
}
