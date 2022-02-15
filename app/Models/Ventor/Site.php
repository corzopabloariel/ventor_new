<?php

namespace App\Models\Ventor;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transport;

use App\Models\Ventor\Slider;
use App\Models\Ventor\Newness;
use App\Models\Content;
use App\Models\Family;
use App\Models\Product;
use App\Models\Number;
use App\Models\Text;
use App\Models\User;
use App\Models\Ventor\Ventor;
use App\Http\Resources\ProductResource;
use App\Models\Ventor\Api;
use PDF;

class Site
{
    private String $page, $part, $subpart, $product, $brand, $search, $return, $route;
    private Bool $isDesktop;
    private Request $request;
    private User $user;
    private $args;
    function __construct($page) {

        $this->page = $page;
        $this->isDesktop = true;
        $this->return = 'normal';
        $this->args = array();
        $this->subpart = "";
        $this->product = "";
        $this->brand = "";
        $this->search = "";
        $this->route = "";

    }
    public function setRequest(Request $request) {

        $this->request = $request;

    }
    public function setArgs($args) {

        $this->args = $args;

    }
    public function setPart(String $part) {

        $this->part = $part;

    }
    public function setSubPart(String $subpart) {

        $this->subpart = $subpart;

    }
    public function setProduct(String $product) {

        $this->product = $product;

    }
    public function setBrand(String $brand) {

        $this->brand = $brand;

    }
    public function setSearch(String $search) {

        $this->search = $search;

    }
    public function setReturn(String $return) {

        $this->return = $return;

    }
    public function setIsDesktop(Bool $isDesktop) {

        $this->isDesktop = $isDesktop;

    }
    public function setRoute(String $route) {

        $this->route = $route;

    }
    public function setUser(User $user) {

        $this->user = $user;

    }

    public function slider() {

        $sliders = Slider::section($this->page)->orderBy('order')->get();
        if ($sliders->isNotEmpty()) {

            $value = collect($sliders)->map(function($x) {

                $img = null;
                if (isset($x->image['i'])) {

                    $img = 'https://ventor.com.ar/'.$x->image['i'];//asset($x->image['i']);

                }
                return array(
                    'image' => $img,
                    'text' => $x->text
                );

            })->toArray();
            return $value;

        } else {

            return null;

        }
    }

    public function content() {
        $content = Content::section($this->page);
        if (!$content)
            return null;
        return $content->data;
    }

    public function pdf() {

        $fields = array(
            'simple' => 1,
            'price' => 1,
            'pdf' => 1,
            'paginate' => 1000,
            'method' => 'PUT'
        );
        if (!empty($this->part)) {

            $fields['part'] = $this->part;

        }
        if (!empty($this->subpart)) {

            $fields['subpart'] = $this->subpart;

        }
        if (!empty($this->brand)) {

            $fields['brand'] = $this->brand;

        }
        if (!empty($this->args)) {

            if (!empty($this->args['search'])) {

                $fields['search'] = str_replace(' ', '+', $this->args['search']);
                unset($this->args['search']);

            }
            foreach($this->args AS $k => $v) {

                $fields[$k] = $v;

            }

        }
        $this->request->request->add($fields);
        $data = (new \App\Http\Controllers\API\ProductController)->index($this->request);
        for ($page = 2; $page <= $data['total']['pages']; $page ++) {

            $fields['page'] = $page;
            $request->request->add($fields);
            $dataAux = (new \App\Http\Controllers\API\ProductController)->index($request);
            if (!empty($dataPage) && !$dataPage['error'] && $dataPage['status'] == 202) {

                $data['products'] = array_merge($data['products'], $dataPage['products']);

            }

        }
        $data['products'] = collect($data['products'])->map(function($product, $i) {

            return 
            '<div style="float: left; width: 33%; margin-bottom:5px; '.(($i + 1) % 3 != 0 ? 'margin-right:.5%' : '').'">' .
                '<p class="code" style="background-color: '.($product['family']['color']['color'] ?? '#767676').'; color: #fff;border-top-right-radius: .6em;border-top-left-radius: .6em;padding: .6em;text-align: right;margin:0;line-height: 0.7em;"><span style="float: left;font-weight: 600;">'.$product['price'].'</span>'.$product['code'].'</p>' .
                '<div style="background-image: url('.$product['image']['url'].'); background-position: center center; background-repeat: no-repeat; border: 1px solid;border-bottom-right-radius: .6em;border-bottom-left-radius: .6em;margin-top: -1px; border-color: '.($product['family']['color']['color'] ?? '#767676').'; background-size: auto 100%;">' .
                    '<div style="padding: .6em;background-color: rgba(255, 255, 255, .4);border-bottom-right-radius: .6em;">' .
                        '<div style="height: 105px;font-size: 11px;line-height: 13px; color: #333">'.$product['name'].'</div>' .
                    '</div>' .
                '</div>' .
            '</div>' .
            (($i + 1) % 3 == 0 ? '<div style="clear: left;"></div>' : '');

        })->toArray();
        $data['products'] = array_chunk($data['products'], 18);
        $pdf = \PDF::loadView('page.pdf', $data);
        return $pdf->output();

    }

    public function modal() {

        $data = array();
        return responseReturn(false, '', 0, 200, $data);
    }

    public function api() {

        switch($this->page) {

            case 'data':// Quito API

                $request = new \App\Http\Requests\UserRequest();
                $request->setMethod('POST');
                $fields = array();
                foreach($this->request->data AS $data) {

                    $fields[$data['name']] = $data['value'];

                }
                $request->request->add($fields);
                $data = (new \App\Http\Controllers\API\UserController)->update($request, $this->user);
                return $data;

            break;
            case 'brands':// Quito API

                $request = new \Illuminate\Http\Request();
                $request->setMethod('PUT');
                $request->request->add(
                    array('method' => 'PUT')
                );
                if (!empty($this->part)) {

                    $request->request->add(
                        array('part' => $this->part)
                    );

                }
                if (!empty($this->subpart)) {

                    $request->request->add(
                        array('subpart' => $this->subpart)
                    );

                }
                if (!empty($this->brand)) {

                    $request->request->add(
                        array('brand' => $this->brand)
                    );

                }
                if (!empty($this->args)) {

                    if (!empty($this->args['search'])) {

                        $request->request->add(
                            array('search' => str_replace(' ', '+', $this->args['search']))
                        );

                    }
                    foreach($this->args AS $k => $v) {

                        $request->request->add(
                            array($k => $v)
                        );

                    }

                }
                $data = (new \App\Http\Controllers\API\ProductController)->brands($request);
                if ($data['error']) {

                    return $data;

                }
                return $data;

            break;
            case 'parte':// Quito API

                $requestProducts = new \Illuminate\Http\Request();
                $requestProducts->setMethod('POST');
                $fields = array();
                $fields['method'] = 'POST';
                if (!empty($this->part)) {

                    $fields['part'] = $this->part;

                }
                if (!empty($this->subpart)) {

                    $fields['subpart'] = $this->subpart;

                }
                if (!empty($this->brand)) {

                    $fields['brand'] = $this->brand;

                }
                if (!empty($this->args)) {

                    if (!empty($this->args['search'])) {

                        $fields['search'] = str_replace(' ', '+', $this->args['search']);

                    }
                    foreach($this->args AS $k => $v) {

                        $fields[$k] = $v;

                    }

                }
                if (\Auth::check()) {

                    $fields['userId'] = session()->has('accessADM') ? session()->get('accessADM') : \Auth::user()->id;
                    $fields['price'] = 1;
                    if (session()->has('markup')) {

                        $fields['markup'] = session()->get('markup');

                    }

                }
                $requestProducts->request->add($fields);
                $dataCartProducts = null;
                $data = (new \App\Http\Controllers\API\ProductController)->index($requestProducts);
                if ($data['error']) {

                    return $data;

                }
                $paginator = new PaginatorApi($data['total']['products'], $data['total']['pages'], $data['page'], $data['slug']);
                $markup = session()->has('markup') ? session()->get('markup') : 'costo';
                if (\Auth::check()) {

                    $request = new \Illuminate\Http\Request();
                    $request->setMethod('GET');
                    $request->request->add(['method' => 'GET']);
                    $userId = session()->has('accessADM') ? session()->get('accessADM') :  \Auth::user()->id;
                    if ($markup == 'costo') {

                        $data['cart'] = (new \App\Http\Controllers\API\CartController)->show($request, $userId);

                    }
                    $dataCartProducts = (new \App\Http\Controllers\API\CartController)->products($request, $userId, 0);

                }
                $data['paginator'] = $paginator->gets();
                $data['filtersLabels'] = isset($data['elements']) ?
                    collect($data['elements'])->map(function($v, $k) use ($data) {

                        if (!isset($data['request'][$k])) {

                            return '';

                        }
                        return '<li class="filters__labels__item" data-element="'.$k.'" data-value="'.$data['request'][$k].'"><span class="filter-label">'.$v.'<i class="fas fa-times"></i></li>';

                    })->join(' ') :
                    '';
                $data['productsHTML'] = $data['products']->map(function($productEntity) use ($dataCartProducts, $markup, $requestProducts) {

                    $product = $productEntity->toArray($requestProducts);
                    return view(
                        'components.public.product',
                        array(
                            'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['path']) : null,
                            'product'   => $product,
                            'isDesktop' => $this->isDesktop,
                            'markup'    => $markup
                        )
                    )->render();

                })->join('');
                if (empty($data['productsHTML'])) {

                    $data['productsHTML'] .= '<div class="alert-errors --noresult">' .
                        '<i class="alert-errors__icon --noresult fas fa-search-location"></i>' .
                        '<p class="alert-errors__title --noresult">¡Uupss!</p>' .
                        '<p class="alert-errors__text --noresult">En este momento no hay productos con estas características</p>' .
                        '<p class="alert-errors__text --bold">Por favor intentá nuevamente con otra búsqueda</p>' .
                    '</div>';

                }
                return $data;

            break;
            case 'producto':// Quito API

                if (!\Auth::check()) {

                    return array(
                        'error'     => true,
                        'message'   => 'Ingrese a su cuenta para poder acceder a la información'
                    );

                }
                $request = new \Illuminate\Http\Request();
                $request->setMethod('PUT');
                $request->request->add(
                    array('method' => 'PUT')
                );
                $request->request->add(
                    array(
                        'code' => $this->args['code'],
                        'userId' => $this->args['userId'] ?? NULL,
                        'on' => $this->args['on'] ?? NULL
                    )
                );
                if ($this->args['type'] == 'stock') {

                    $data = (new \App\Http\Controllers\API\ProductController)->stock($request);

                }
                if ($this->args['type'] == 'price') {

                    $data = (new \App\Http\Controllers\API\ProductController)->price($request);

                }
                return $data;

            break;
            case 'client':// Quito API

                if (isset($this->args['type'])) {

                    switch($this->args['type']) {

                        case 'access':
                        case 'select':

                            $data = (new \App\Http\Controllers\API\ClientController)->show($this->request, $this->args['client']);
                            if (
                                !$data['error'] &&
                                count($data['elements']) > 0 &&
                                $this->args['type'] == 'access'
                            ) {

                                session(['accessADM' => $this->args['client']]);

                            }

                        break;
                        case 'logout':

                            session()->forget('accessADM');
                            return null;

                        break;
                        case 'update':

                            foreach($this->args AS $k => $v) {

                                $this->request->request->add(
                                    array($k => $v)
                                );

                            }
                            $data = (new \App\Http\Controllers\API\ClientController)->update($this->request);

                        break;
                        default:

                            $data = (new \App\Http\Controllers\API\ClientController)->action($this->request, $this->args['client'], $this->args['type']);

                    }

                }
                return $data;

            break;
            case 'clients':// Quito API

                if (!empty($this->args)) {

                    foreach($this->args AS $k => $v) {

                        $this->request->request->add(
                            array($k => $v)
                        );

                    }

                }
                $data = (new \App\Http\Controllers\API\ClientController)->index($this->request);
                return $data;

            break;
            case 'transports':// Quito API

                $data = (new \App\Http\Controllers\API\TransportController)->index($this->request);
                return $data;

            break;
            case 'cart':// Quito API

                if (!\Auth::check()) {

                    return array(
                        'error'     => true,
                        'message'   => 'Ingrese a su cuenta para poder agregar al carrito'
                    );

                }
                // Agrega o Quita un producto del carrito
                if (isset($this->args['append'])) {
                    
                    $dataCart = (new \App\Http\Controllers\API\CartController)->products($this->request, $this->args['userId'], 0);
                    if ($dataCart['error']) {
    
                        return $dataCart;
    
                    }
                    $data = $dataCart['element'];
                    if ($this->args['append']) {

                        if (count($data) > 0) {

                            $flagFind = false;
                            for ($i = 0; $i < count($data); $i ++) {

                                if ($data[$i]['product'] == $this->args['code']) {

                                    $flagFind = true;
                                    $data[$i]['quantity'] = $this->args['quantity'];
                                    break;

                                }

                            }
                            if (!$flagFind) {

                                $data[] = array(
                                    'product'   => $this->args['code'],
                                    'quantity'  => $this->args['quantity']
                                );

                            }

                        } else {

                            $data[] = array(
                                'product'   => $this->args['code'],
                                'quantity'  => $this->args['quantity']
                            );

                        }

                    } else {

                        if (count($data) > 0) {

                            for ($i = 0; $i < count($data); $i ++) {

                                if ($data[$i]['product'] == $this->args['code']) {

                                    array_splice($data, $i, 1);
                                    break;

                                }

                            }

                        }

                    }
                    $request = new \App\Http\Requests\CartRequest();
                    $request->setMethod('POST');
                    $request->request->add(
                        array(
                            'method' => 'POST',
                            'user_id' => $this->args['userId'],
                            'data' => $data
                        )
                    );
                    $data = (new \App\Http\Controllers\API\CartController)->store($request);
                    return $data;

                }
                if (isset($this->args['show'])) {

                    $dataCart = (new \App\Http\Controllers\API\CartController)->show($this->request, $this->args['userId']);
                    if ($dataCart['error']) {
    
                        return $dataCart;
    
                    }
                    $dataCart['elements'] = $dataCart['elements']->toArray($this->request);
                    $dataCart['productsHTML'] = collect($dataCart['elements']['data'])->map(function($product) {

                        return view(
                            'components.product.cart',
                            array(
                                'product'   => $product
                            )
                        )->render();

                    })->join('');
                    return $dataCart;

                }
                if (isset($this->args['admin'])) {

                    if (isset($this->args['cartId'])) {

                        $dataCart = (new \App\Http\Controllers\API\CartController)->destroy($this->request, $this->args['cartId']);
                        if ($dataCart['error']) {
        
                            return $dataCart;
        
                        }
                        return $dataCart;

                    }
                    $request = new \Illuminate\Http\Request();
                    $request->setMethod('POST');
                    $request->request->add(['method' => 'POST']);
                    $request->request->add(['admin' => 1]);
                    $dataCart = (new \App\Http\Controllers\API\CartController)->products($request, $this->args['userId'], 2);
                    if ($dataCart['error']) {
    
                        return $dataCart;
    
                    }
                    return $dataCart;

                }

            break;
            case 'order':// Quito API

                $request = new \Illuminate\Http\Request();
                $request->setMethod('POST');
                $request->request->add(['method' => 'POST']);
                $request->request->add(['user_id' => $this->args['userId']]);
                $request->request->add(['data' => collect($this->args)->except(['userId'])->toJson()]);
                $request->request->add(['simple' => 1]);
                $data = (new \App\Http\Controllers\API\OrderController)->store($request);
                return $data;

            break;
            case "mail":

                set_time_limit(600);
                $url = 'http://'.config('app.api').'/mail';
                if (isset($this->args['userId'])) {

                    $userId = $this->args['userId'];
                    $fields = array(
                        'user_id' => $userId,
                        'data' => collect($this->args)->except(['userId'])->toJson()
                    );

                } else {

                    $fields = array(
                        'data' => collect($this->args)->toJson()
                    );

                }
                $fields['method'] = 'POST';
                $request = new \Illuminate\Http\Request();
                $request->setMethod('POST');
                $request->request->add($fields);
                $data = (new \App\Http\Controllers\API\MailController)->index($request);
                return $data;

            break;
            case "aplicacion":// Quito API

                $url = 'http://'.config('app.api').'/applications/elements';
                $this->args['method'] = 'POST';
                $this->args['image'] = 1;
                $this->args['price'] = 1;
                $request = new \Illuminate\Http\Request();
                $request->setMethod('POST');
                $request->request->add($this->args);
                $data = (new \App\Http\Controllers\API\ApplicationController)->elements($request);
                if (isset($data['products'])) {

                    $dataCartProducts = null;
                    $markup = session()->has('markup') ? session()->get('markup') : 'costo';
                    if (\Auth::check()) {

                        $userId = session()->has('accessADM') ? session()->get('accessADM') :  \Auth::user()->id;
                        $request = new \Illuminate\Http\Request();
                        $request->setMethod('GET');
                        $request->request->add(['method' => 'GET']);
                        if ($markup == 'costo') {

                            $data['cart'] = (new \App\Http\Controllers\API\CartController)->show($request, $userId);

                        }
                        $dataCartProducts = (new \App\Http\Controllers\API\CartController)->products($request, $userId, 0);

                    }
                    $data['productsHTML'] = collect($data['products'])->map(function($application, $key) use($dataCartProducts, $markup) {

                        return view(
                            'components.public.application',
                            array(
                                'dataCartProducts'  => $dataCartProducts,
                                'application'       => $application,
                                'markup'            => $markup
                            )
                        )->render().'<hr/>';

                    })->join('');

                }
                return $data;

            break;
            case 'seller':// Quito API

                $data = \Auth::user()->seller();
                return $data;

            break;

        }

    }
    
    // TODO  Análisis de deuda, Faltantes, Comprobantes, Mi perfil
    public function elements($pdf = 0) {

        $elements = [
            "page" => $this->page,
            "sliders" => self::slider(),
            "content" => self::content(),
            "title" => "Ventor SACei",
            "description" => "Distribuidor Mayorista de Repuestos Automotor y Correas"
        ];
        switch($this->page) {
            case 'home':

                $view = view(
                    'components.page.home',
                    array(
                        'newness'   => Newness::gets(configs("NEWS_LIMIT", 3)),
                        'families'  => Family::gets()
                    )
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'slider'    => self::slider(),
                    'script'    => 'home'
                );

            break;
            case 'empresa':

                $view = view(
                    'components.page.empresa',
                    self::content()
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'slider'    => self::slider(),
                    'script'    => 'empresa'
                );

            break;
            case 'calidad':

                $view = view(
                    'components.page.calidad',
                    self::content()
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'slider'    => self::slider(),
                    'script'    => 'calidad'
                );

            break;
            case "novedades":

                $view = view(
                    'components.page.home_newness',
                    array(
                        'items' => Newness::gets(0),
                        'all'   => true
                    )
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => 'home'
                );

            break;
            case "descargas":

                $order = Content::section("categoriesDownload")->data;
                $downloads = Download::gets($order);
                $view = view(
                    'components.page.descargas',
                    array(
                        'downloads' => $downloads,
                        'program'   => configs("LINK_PROGRAMA")
                    )
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => 'descargas'
                );

            break;
            case "aplicacion":

                $params = self::paramsApplication($this->request->path());
                $typeImage = pathinfo(config('app.static').'img/parabrisas.jpg', PATHINFO_EXTENSION);
                $brands = (new \App\Http\Controllers\API\ProductController)->brands($this->request);
                $elements = array(
                    'brands'    => !$brands['error'] ? $brands['brands'] : array(),
                    'params'    => $params,
                    'elements'  => array()
                );
                if (session()->has('markup')) {

                    $elements['markup'] = session()->get('markup');

                }
                $view = view(
                    'components.page.aplicacion',
                    $elements
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'slider'    => array(
                        array('image' => 'data:image/'.$typeImage.';base64,'.base64_encode(file_get_contents(config('app.static').'img/parabrisas.jpg')), 'text' => null)
                    ),
                    'script'    => 'aplicacion'
                );

            break;
            case 'pagos':

                $view = view(
                    'components.page.cliente_pagos',
                    array(
                        'banco' => Text::where("name", "CUENTAS BANCARIAS")->first(),
                        'pagos' => Text::where("name", "PAGOS VIGENTES")->first()
                    )
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => 'pagos'
                );

            break;
            case 'transmision':

                $view = view(
                    'components.page.cliente_transmision'
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => 'transmision'
                );

            break;
            case "contacto":

                $view = view(
                    'components.page.contacto',
                    array(
                        'numeros' => Number::orderBy("order")->get()
                    )
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => 'contacto'
                );

            break;
            case "productos":
            case "parte":// NEW

                $initial_time = microtime(true);
                $params = self::params($this->request->path());
                $elements['page'] = 'parte';
                $elements['params'] = $params;
                $elements['orderBy'] = $this->request->has('orderBy') ? $this->request->get('orderBy') : 'code';
                $elements['type'] = $this->request->has('type') ? $this->request->get('type') : null;
                $elements['args'] = $this->args;
                $elements['lateral'] = Family::gets();
                if (session()->has('markup')) {

                    $elements['markup'] = session()->get('markup');

                }
                $final_time = microtime(true);
                $loading_time = $final_time - $initial_time;
                $elements['time'] = $loading_time.' segundos';
                $view = view(
                    'components.page.productos',
                    $elements
                )->render();
                return array(
                    'view'          => $view,
                    'page'          => 'basic',
                    'script'        => 'productos',
                    'scriptParams'  => array(
                        'currentPage'   => $this->request->has('page') ? $this->request->get('page') : '1'
                    )
                );

            break;
            case "producto":// NEW

                $request = new \Illuminate\Http\Request();
                $request->setMethod('PATCH');
                $fields = array(
                    'method' => 'PATCH',
                    'code' => $this->args['code'],
                    'get' => true,
                    'price' => true,
                    'userId' => (\Auth::check() ? \Auth::user()->id : NULL),
                    'markup' => session()->has('markup') && session()->get('markup') != 'costo'
                );
                $request->request->add($fields);
                $product = Product::one($request, $this->args['code']);
                $productResource = (new ProductResource($product['products'][0]))->toArray($request);
                $referer = request()->headers->get('referer');
                if (\Auth::check()) {

                    $userId = session()->has('accessADM') ? session()->get('accessADM') :  \Auth::user()->id;
                    $requestCart = new \Illuminate\Http\Request();
                    $requestCart->setMethod('POST');
                    $fields = array(
                        'method' => 'POST',
                        'code' => $productResource['code']
                    );
                    $requestCart->request->add($fields);
                    $dataCartProducts = (new \App\Http\Controllers\API\CartController)->products($requestCart, $userId, 0);

                }
                $markup = session()->has('markup') ? session()->get('markup') : 'costo';
                $product = view(
                    'components.product.file',
                    array(
                        'product'   => $productResource,
                        'referer'   => empty($referer) ? route('products_part_subpart_brand', array('part' => $productResource['family']['name_slug'], 'subpart' => $productResource['subpart']['name_slug'], 'brand' => $productResource['brands'][0]['slug'])) : $referer,
                        'markup'    => $markup,
                        'cart'      => isset($dataCartProducts) ? $dataCartProducts : null
                    )
                )->render();
                return array(
                    'product'   => $product,
                    'page'      => 'producto',
                    'script'    => 'producto'
                );

            break;
            case "order": // NEW

                if ($this->return == 'pdf') {

                    $request = new \Illuminate\Http\Request();
                    $request->setMethod('POST');
                    $request->request->add(
                        array(
                            'method'    => 'POST',
                            'simple'    => 1,
                            'price'     => 1,
                            'pdf'       => 1
                        )
                    );
                    $elements = (new \App\Http\Controllers\API\OrderController)->products($request, $this->args['orderId']);
                    $elements = $elements->toArray($request);
                    $elements['products'] = $elements['products']->toArray($request);
                    $elements['ventor'] = Ventor::first();
                    $elements['request'] = $request;
                    $pdf = \PDF::loadView('page.pdf_order', $elements);
                    return $pdf->output();

                }

            break;
            case 'client':

                $elements = array();
                if ($this->args['action'] == 'mis-datos') {

                    $userId = session()->has('accessADM') ? session()->get('accessADM') :  \Auth::user()->id;
                    $data = (new \App\Http\Controllers\API\ClientController)->show($this->request, $userId);
                    if (!$data['error']) {

                        $elements['data'] = $data['elements'][0]->toArray($this->request);
                        $elements['userId'] = $userId;

                    }

                }
                $view = view(
                    'components.client.'.$this->args['action'],
                    $elements
                )->render();
                return array(
                    'view'      => $view,
                    'page'      => 'basic',
                    'script'    => $this->args['action']
                );

            break;

        }
        return $elements;

    }

    public static function paramsApplication(String $path) {

        $path = str_replace('aplicacion', '', $path);
        if (strpos($path, ':') !== false) {

            $path = str_replace(':', '', $path);

        }
        $params = array();
        $params[] = null;// brand
        $params[] = null;// model
        $params[] = null;// year
        if (!empty($path)) {

            $params = explode(',', $path);
            if (strpos($params[2], '%3E') !== false) {

                $params[2] = str_replace('%3E', '>', $params[2]);

            }

        }
        if (!empty($params[0])) {

            $applicationBrand = \App\Models\ApplicationBrand::where('slug', $params[0])->first();
            $params[0] = $applicationBrand->id ?? null;

        }
        if (!empty($params[1])) {

            $applicationModel = \App\Models\ApplicationModel::where('slug', $params[1])->first();
            $params[1] = $applicationModel->id ?? null;

        }
        if (!empty($params[2])) {

            $applicationYear = \App\Models\ApplicationYear::where('name', $params[2])->first();
            $params[2] = $applicationYear->id ?? null;

        }
        return $params;

    }
    public static function params(String $path) {

        $params = array();
        $params[] = null;// part
        $params[] = null;// subpart
        $params[] = null;// brand
        $params[] = null;// search
        if (str_contains($path, ',')) {

            list($path, $search) = explode(',', $path);
            $params[3] = $search;

        }
        if (str_contains($path, '__')) {

            list($path, $brand) = explode('__', $path);
            $params[2] = $brand;

        }
        if (str_contains($path, '/subparte:')) {

            list($path, $subpart) = explode('/subparte:', $path);
            $params[1] = $subpart;

        }
        if (str_contains($path, 'parte:')) {

            list($path, $part) = explode('parte:', $path);
            $params[0] = $part;

        }
        return $params;

    }

}
