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

        $response = Http::withHeaders([
            'Content-Type: application/json',
            'X-Requested-With: XMLHttpRequest',
            'Authorization: Bearer '.env('PASSPORT_TOKEN')
        ])->get($url);
        
        // You need to parse the response body
        // This will parse it into an array
        $response = json_decode($response->getBody(), true);
        dd($response);
    }
}