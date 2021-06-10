<?php

namespace App\Models\Ventor;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Config;

class Api
{
    public static function data(String $url, Request $request)
    {
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
        try {
            $config = configs("TOKEN_PASSPORT");
            $token = "";
            if (!empty($config) && str_contains($config, '{"access_token":')) {
                $config = json_decode($config, true);
                $token = $config["access_token"];
            } else {
                $token = self::login($config);
            }
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
            dd($th);
        }
    }

    public static function login($data) {
        \DB::table('errors')->insert([
            'host' => $_SERVER['HTTP_HOST'],
            'description' => $data,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".config('app.api')."/login");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=pc&password=56485303");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $remote_server_output = curl_exec ($ch);
        curl_close ($ch);
        Config::create([
            'name' => 'TOKEN_PASSPORT',
            'value' => $remote_server_output,
            'visible' => false
        ], true);
        return $remote_server_output;
    }
}