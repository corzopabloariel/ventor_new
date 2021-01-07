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
            $response = Http::get($url);
            $response = json_decode($response->getBody(), true);
            return $response;
        } catch (\Throwable $th) {
            return null;
        }
    }
}