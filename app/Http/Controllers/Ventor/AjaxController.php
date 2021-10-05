<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;

class AjaxController extends Controller
{
    public function products(Request $request) {
        $args = collect($request->all())->filter(function($item) {
            return $item['name'] != 'route';
        })->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['value']];
        });
        $site = new Site('parte');
        $site->setPart($args['part']);
        $site->setReturn('api');
        $site->setRequest($request);
        $data = $site->elements();
        return $data;
    }
}
