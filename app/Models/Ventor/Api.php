<?php

namespace App\Models\Ventor;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
            $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiZjljOGFlYzQ2OWEwODc2YTg3NGFiYTE5NTEwNmUxYTIwZDI1ZTQzYWZlMzU3MzQ0ZGVlOGY4ZjI1MWZhM2I2ZTVlYmFiMDM3NjExMTI5MDciLCJpYXQiOiIxNjEwMjE2NDkzLjc2NTM0MyIsIm5iZiI6IjE2MTAyMTY0OTMuNzY1MzQ2IiwiZXhwIjoiMTY0MTc1MjQ5My43NjAxMjUiLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.gEnTNMvSjEn3Opmrz8aPKayhS77iUuLwspoOhvrIxF3tRNNb_Q5CD1UFirq3RLChq8NElwkhXI6JvH-ui-VDpVdzsPj0JIFnbxGDpQJb-rrmgiQ5ufOlBJZiPEhPGYRx5KPgtWmPUvwAGSg1v_BDZ2w2plCKdi-5wow6Q2ji6cDHZhgcmrHxMU_AV5pGxdnUO2KiYJ12ayHhjZ_HdU3lGCdovcrGBnt6NaSG2qJnui76Z1kmgjKelwP49XC0XCy5AASQRXTcIWkbKaW1ZcImMyu_skp6KZnFA-7s1WrQ4MXNOFnln81isIEXhRRq8mq0EhnQZIFr0cNiC7ca43ettuJ31vrU1M0yHTWUiv2DZyZ4KLHnMBlaEsXbIB2s8ToSXN5v1jInQB2Pa-kbePktHF4BYnVKLaniFyd2asdxWRnYPp0YBz07V2mOBn3o0bPw2gHLXweQEMplrlFA9uzseCtGPWELCLlH6vPBRgHCSRaQ3Zdx6R8WKJ4ekf90jwISOtCtwe3MC2XnZ3XmRyOV4j8cDJX7cqvEP6qdvqtFS2jPPBEE3FYjGGqwn4sRYnWH7TKxopceAj0nsLwGvT7-cxgDhbzBTw2KfGGVLsAifmN9fABj9ME80JI2FpnMFVi2aIOVItDsM_V7LI9N9JPPKY9NBLqJUHGPztX-A7BAKxw";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                $authorization
            ]);
            curl_setopt($ch, CURLOPT_URL, "https://ventor.com.ar/api/public$url"); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                dd($error_msg);
            }
            curl_close($ch);
            $response = json_decode($data, true);
            return $response;
        } catch (\Throwable $th) {
            dd("AAAAAAAAA");
        }
    }
}