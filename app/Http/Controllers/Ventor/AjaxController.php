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
        if (!empty($args['part'])) {

            $site->setPart($args['part']);
            unset($args['part']);

        }
        if (!empty($args['subpart'])) {

            $site->setSubPart($args['subpart']);
            unset($args['subpart']);

        }
        if (!empty($args['brand'])) {

            $site->setBrand($args['brand']);
            unset($args['brand']);

        }
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->elements();
        return $data;
    }
    public function paginator(Request $request) {
        $slug = $request->slug;
        $slug = str_replace(\URL::to('/').'/', '', $slug);
        list($slug, $argv) = explode('?', $slug);
        $params = Site::params($slug);
        $site = new Site('parte');
        if (!empty($params[0])) {

            $site->setPart($params[0]);

        }
        if (!empty($params[1])) {

            $site->setSubPart($params[1]);

        }
        if (!empty($params[2])) {

            $site->setBrand($params[2]);

        }
        $args = array();
        if (!empty($argv)) {

            $argv = explode('&', $argv);
            foreach($argv AS $a) {
                list($k, $v) = explode('=', $a);
                $args[$k] = $v;
            }

        }
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->elements();
        return $data;
    }
}
