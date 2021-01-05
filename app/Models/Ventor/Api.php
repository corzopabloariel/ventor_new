<?php

namespace App\Models\Ventor;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Api
{
    public static function data($url)
    {
        try {
            $response = Http::get($url);
            $response = json_decode($response->getBody(), true);
            return $response;
        } catch (\Throwable $th) {
            return null;
        }
    }
}