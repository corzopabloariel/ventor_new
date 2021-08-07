<?php

namespace App\Models\Ventor;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Config;

class Api
{
    public static function data(String $url, Request $request, Bool $onlyUrl = false)
    {
        if (!$onlyUrl) {
            if (session()->has('type')) {
                $url .= (str_contains($url, "?") ? "&" : "?") . "type=" . session()->get('type');
                if (session()->get('type') == "nuevos") {
                    $dateStart = date("Y-m-d", strtotime("-1 month"));
                    $dateEnd = date("Y-m-d");
                    if (auth()->guard('web')->check()) {
                        if (!empty(auth()->guard('web')->user()->start))
                            $dateStart = date("Y-m-d", strtotime(auth()->guard('web')->user()->start));
                        if (!empty(auth()->guard('web')->user()->end))
                            $dateEnd = date("Y-m-d", strtotime(auth()->guard('web')->user()->end));
                    }
                    $url .= "&start=$dateStart";
                    $url .= "&end=$dateEnd";
                }
            }
            if(session()->has('markup') && session()->get('markup') != "costo") {
                $discount = auth()->guard('web')->user()->discount / 100;
                $url .= (str_contains($url, "?") ? "&" : "?") . "markup=" . $discount;
            }
            $url .= (str_contains($url, "?") ? "&" : "?") . "paginate=" . configs("PAGINADO", 36);
        }
        try {
            $token = self::token();
            $authorization = "Authorization: Bearer " . $token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                $authorization
            ]);
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                dd($url, $error_msg);
            }
            curl_close($ch);
            if (str_contains($data, 'login') || empty($data)) {
                $token = self::login($data);
            }
            $response = json_decode($data, true);
            return $response;
        } catch (\Throwable $th) {
            \DB::table('errors')->insert([
                'host' => $_SERVER['HTTP_HOST'],
                'description' => $th,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }

    public static function token() {
        $tokenPassport = configs("TOKEN_PASSPORT");
        if (\Auth::check() && \Auth::user()->username != 'pc') {
            $config = \Auth::user()->config;
            if (!empty($config->other) && isset($config->other['passport'])) {
                $tokenPassport = $config->other['passport'];
            } else if (!empty($config->other) && isset($config->other['secret'])) {
                $tokenPassport = $config->other['passport'] ?? null;
            } else if (\Auth::user()->isShowQuantity()) {
                $tokenPassport = null;
            }
        }
        if (empty($tokenPassport) || !empty($tokenPassport) && !str_contains($tokenPassport, '{"access_token":')) {
            $tokenPassport = self::login($tokenPassport);
        }
        $tokenPassport = json_decode($tokenPassport, true);
        $token = $tokenPassport["access_token"];
        return $token;
    }

    public static function login($data) {
        if (!empty($data)) {
            \DB::table('errors')->insert([
                'host' => $_SERVER['HTTP_HOST'],
                'description' => $data,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
        $username = 'pc';
        $password = '56485303';
        $flagWithSecret = false;
        if (\Auth::check()) {
            if (\Auth::user()->isShowQuantity()) {
                $username = \Auth::user()->username;
                if ($username != 'pc') {
                    $password = config('app.pass');
                }
            } else {
                $config = \Auth::user()->config;
                if (!empty($config->other) && isset($config->other['secret'])) {
                    $username = \Auth::user()->username;
                    $password = $config->other['secret'];
                    $flagWithSecret = true;
                }
            }
        }
        $postData = 'username='.$username;
        $postData .= '&password='.$password;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".config('app.api')."/login");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
        if (\Auth::check() && \Auth::user()->username != 'pc' || \Auth::check() && $flagWithSecret) {
            \Auth::user()->setConfig([
                'other' => ['passport' => $remote_server_output]
            ]);
        } else {
            Config::create([
                'name' => 'TOKEN_PASSPORT',
                'value' => $remote_server_output,
                'visible' => false
            ], true);
        }
        return $remote_server_output;
    }
}