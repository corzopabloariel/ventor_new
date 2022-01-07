<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Ventor\Api;
use App\Models\Client;
use App\Models\Transport;

class AjaxController extends Controller
{
    public function pdf(Request $request) {

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
                if ($k == 'page') { continue; }
                $args[$k] = $v;
            }

        }
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('pdf');
        $data = $site->elements();
        return $data;

    }
    public function markup(Request $request) {

        session(['markup' => $request->type]);
        $cart = null;
        if (session()->get('markup') == 'costo') {

            $urlCart = 'http://'.config('app.api').'/carts/1/products/1';
            $dataCart = Api::data($urlCart, $request);
            $cart = $dataCart;

        }
        return response(
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'cart'      => $cart,
                'type'      => session()->get('markup')
            ),
            202
        );

    }
    public function prices(Request $request) {

        $args = array(
            'code'      => $request->code,
            'userId'    => \Auth::check() ? \Auth::user()->id : 1,
            'type'      => 'price'
        );
        $site = new Site('producto');
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        $data['markup'] = session()->has('markup') ? (session()->get('markup') == 'costo' ? 'price' : 'priceMarkup') : 'price';
        return $data;

    }
    public function stock(Request $request) {

        $args = array(
            'code'      => $request->code,
            'userId'    => \Auth::check() ? \Auth::user()->id : 1,
            'type'      => 'stock'
        );
        $site = new Site('producto');
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }
    public function applications(Request $request) {

        $args = collect($request->all())->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['value']];
        })->toArray();
        $site = new Site('aplicacion');
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }
    public function products(Request $request) {

        $args = collect($request->all())->filter(function($item) {
            return $item['name'] != 'route';
        })->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['value']];
        })->toArray();
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
        if (isset($args['type']) && $args['type'] == 'nuevos' && \Auth::check()) {

            $args['userId'] = \Auth::user()->id;

        }
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
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
        if (\Auth::check()) {

            $args['userId'] = \Auth::user()->id;

        }
        if (!empty($params[3])) {

            $args['search'] = $params[3];

        }
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }

    public function client(Request $request) {

        if (\Auth::check()) {

            $site = new Site('client');
            $args = array();
            $args['client'] = $request->client;
            $site->setArgs($args);
            $site->setRequest($request);
            $site->setReturn('api');
            $data = $site->api();
            return $data;

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }

    public function clients(Request $request) {

        if (\Auth::check()) {

            $clients = array();
            if (in_array(\Auth::user()->role, array('ADM', 'EMP'))) {

                $clients = Client::getAll("nrocta")->map(function($c) {

                    if (!$c->user()) {

                        return null;

                    }
                    return array(
                        'id'    => $c->user()->id,
                        'text'  => '#'.$c->nrocta.' -> '.$c->razon_social
                    );

                })->filter(function($c) {

                    return !empty($c);

                })->toArray();

            }
            if (in_array(\Auth::user()->role, array('VND'))) {

                $clients = Client::getAll("nrocta", "ASC", \Auth::user()->dockets)->map(function($c) {

                    if (!$c->user()) {

                        return null;

                    }
                    return array(
                        'id'    => $c->user()->id,
                        'text'  => '#'.$c->nrocta.' -> '.$c->razon_social
                    );

                })->filter(function($c) {

                    return !empty($c);

                })->toArray();

            }
            return array(
                'error'     => false,
                'status'    => 202,
                'clients'   => array_values($clients)
            );

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }

    public function transports(Request $request) {

        if (\Auth::check()) {

            $transports = '';
            $transports = Transport::getAll("code")->map(function($c) {

                return array(
                    'id'    => $c->code,
                    'text'  => '#'.$c->code.' -> '.$c->description
                );

            })->toArray();
            return array(
                'error'         => false,
                'status'        => 202,
                'transports'    => $transports
            );

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }

    public function cartProducts(Request $request) {

        $args = $request->all();
        if (\Auth::check()) {

            $args['userId'] = \Auth::user()->id;

        }
        $site = new Site('cart');
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }

    public function orderNew(Request $request) {

        $args = $request->all();
        if (\Auth::check()) {

            $args['status'] = 'new';
            $args['userId'] = \Auth::user()->id;
            $site = new Site('order');
            $site->setArgs($args);
            $site->setRequest($request);
            $site->setReturn('api');
            $data = $site->api();
            return $data;

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }
    public function orderPdf(Request $request, $order) {

        $args = array();
        if (\Auth::check()) {

            $args['orderId'] = $order;
            $args['userId'] = \Auth::user()->id;
            $site = new Site('order');
            $site->setArgs($args);
            $site->setRequest($request);
            $site->setReturn('pdf');
            $data = $site->elements();
            return $data;

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }
    public function mail(Request $request) {

        if (\Auth::check()) {

            $args = $request->all();
            $args['emails'] = configs('EMAILS_ORDER');
            if ($args['is_test']) {

                $args['emails'] = str_replace('pedidos.ventor@gmx.com;', '', $args['emails']);

            }
            $args['userId'] = \Auth::user()->id;
            $site = new Site('mail');
            $site->setArgs($args);
            $site->setRequest($request);
            $site->setReturn('api');
            $data = $site->api();
            return $data;

        }
        return array(
            'error'     => false,
            'status'    => 401
        );

    }
}
