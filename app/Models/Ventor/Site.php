<?php

namespace App\Models\Ventor;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Ventor\Slider;
use App\Models\Ventor\Newness;
use App\Models\Content;
use App\Models\Family;
use App\Models\Client;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Product;
use App\Models\Order;
use App\Models\Number;
use App\Models\Transport;
use App\Models\Text;
use App\Models\Application;
use App\Models\Ventor\Cart;

use App\Models\Ventor\Api;

class Site
{
    private String $page, $part, $subpart, $product, $brand, $search, $return;
    private Bool $isDesktop;
    private Request $request;
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

    public function slider() {
        $sliders = Slider::section($this->page)->orderBy("order")->get();
        if ($sliders->isNotEmpty()) {
            $value = collect($sliders)->map(function($x) {
                $img = null;
                if (isset($x->image["i"]))
                    $img = $x->image["i"];
                return ["image" => $img, "text" => $x->text];
            })->toArray();
            return $value;
        } else
            return null;
    }

    public function content() {
        $content = Content::section($this->page);
        if (!$content)
            return null;
        return $content->data;
    }

    public function pdf() {//TODO
        $elements = self::elements(1);
        return $elements;
    }

    public function modal() {

        $data = array();
        return responseReturn(false, '', 0, 200, $data);
    }

    public function elements($pdf = 0) {
        if ($pdf) {
            $elements = [];
        } else {
            $elements = [
                "page" => $this->page,
                "sliders" => self::slider(),
                "content" => self::content(),
                "title" => "Ventor SACei",
                "description" => "Distribuidor Mayorista de Repuestos Automotor y Correas"
            ];
        }
        switch($this->page) {
            case "parte":
            case "subparte":
            case "pedido":
            case "checkout":
                if (auth()->guard('web')->check()) {
                    if (!session()->has('accessADM')) {
                        if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                            $elements["clients"] = Client::getAll("nrocta");
                        if (auth()->guard('web')->user()->role == "VND") {
                            $elements["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->dockets);
                        }
                    }
                }
                break;
        }
        switch($this->page) {
            case "home":
                $elements["newness"] = Newness::gets(configs("NEWS_LIMIT", 3));
                $elements["families"] = Family::gets();
                break;
            case "novedades":
                $elements["newness"] = Newness::gets(0);
                break;
            case "descargas":
                $elements["order"] = Content::section("categoriesDownload")->data;
                $elements["downloads"] = Download::gets();
                $elements["program"] = configs("LINK_PROGRAMA");
                break;
            case "productos":
                $elements["families"] = Family::gets();
                break;
            case "aplicacion":
                // TODO
                if ($this->return == 'json') {
                    if (count($this->args) == 1) {
                        $application = Application::models($this->args[0]);
                        $applicationOptions = collect($application)
                            ->map(function($opt) {
                                return [
                                    'value' => $opt['slug'],
                                    'label' => $opt['name']
                                ];
                            })
                            ->toArray();
                        return array(
                            'data' => $application,
                            'dataOptions' => $applicationOptions
                        );
                    }
                    if (count($this->args) == 2) {
                        $application = Application::years($this->args);
                        return array(
                            'data' => $application,
                            'dataOptions' => collect($application)
                                    ->map(function($opt) {
                                        return [
                                            'value' => $opt[0],
                                            'label' => $opt[0]
                                        ];
                                    })
                                    ->toArray()
                        );
                    }
                    return $this->args;
                }
                if (count($this->args) > 0) {
                    $elements['brand'] = $this->args[0];
                    $elements['model'] = $this->args[1];
                    $elements['year'] = $this->args[2] ?? 0;
                    $models = Application::models($this->args[0]);
                    $elements['models'] = array(
                        'data' => $models,
                        'dataOptions' => collect($models)
                            ->map(function($opt) use ($elements) {
                                $selected = $opt['slug'] == $elements['model'] ? 'selected' : '';
                                return "<option {$selected} value='{$opt['slug']}'>{$opt['name']}</option>";
                            })
                            ->join('')
                    );
                    $years = Application::years($this->args);
                    $elements['years'] = array(
                        'data' => $years,
                        'dataOptions' => collect($years)
                            ->map(function($opt) use ($elements) {
                                $selected = $opt[0] == $elements['year'] ? 'selected' : '';
                                return "<option {$selected} value='{$opt[0]}'>{$opt[0]}</option>";
                            })
                            ->join('')
                    );
                    $elements['products'] = Application::products($this->args);
                }
                $type = pathinfo(config('app.static').'img/parabrisas.jpg', PATHINFO_EXTENSION);
                $elements['image'] = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(config('app.static').'img/parabrisas.jpg'));
                $elements['brands'] = Application::brands();
                $elements['brandsOptions'] = collect($elements['brands'])
                    ->map(function($opt) use ($elements) {
                        $selected = isset($elements['brand']) && $elements['brand'] == $opt['slug'] ? 'selected' : '';
                        return "<option {$selected} value='{$opt['slug']}'>{$opt['name']}</option>";
                    })
                    ->join('');
                break;
            case "contacto":
                $elements["number"] = Number::orderBy("order")->get();
                break;
            case "pagos":
                $elements["banco"] = Text::where("name", "CUENTAS BANCARIAS")->first();
                $elements["pagos"] = Text::where("name", "PAGOS VIGENTES")->first();
                break;
            case "checkout":
                if (session()->has('nrocta_client') || session()->has('accessADM')) {
                    $nrocta = session()->has('accessADM') ? session()->get('accessADM')->docket : session()->get('nrocta_client');
                    $elements["client"] = Client::one($nrocta, "nrocta");
                    $elements["transport"] = Transport::gets($elements["client"]->_id ?? "");
                } else
                    $elements["transport"] = Transport::gets(\auth()->guard('web')->user()->uid ?? "");
                break;
            case "parte":

                if ($this->return == 'pdf') {

                    $url = 'http://'.config('app.api').'/products';
                    $urlParams = array();
                    if (!empty($this->part)) {

                        $url .= '/part:'.$this->part;

                    }
                    if (!empty($this->subpart)) {

                        $url .= '/subpart:'.$this->subpart;

                    }
                    if (!empty($this->brand)) {

                        $url .= '__'.$this->brand;

                    }
                    if (!empty($this->args['search'])) {

                        $url .= ','.str_replace(' ', '+', $this->args['search']);
                        unset($this->args['search']);

                    }
                    if (!empty($this->args)) {

                        foreach($this->args AS $k => $v) {

                            $urlParams[] = $k.'='.$v;

                        }

                    }
                    if (!empty($urlParams)) {

                        $url .= '?'.implode('&', $urlParams).'&simple&price&paginate=500';

                    }
                    $data = Api::data($url, $this->request);
                    for ($page = 2; $page <= $data['total']['pages']; $page ++) {

                        $dataPage = Api::data($url.'&page='.$page, $this->request);
                        if (!empty($dataPage) && !$dataPage['error'] && $dataPage['status'] == 202) {

                            $data['products'] = array_merge($data['products'], $dataPage['products']);

                        }

                    }
                    return $data;

                }
                if ($this->return == 'api') {

                    $url = 'http://'.config('app.api').'/products';
                    $urlParams = array();
                    if (!empty($this->part)) {

                        $url .= '/part:'.$this->part;

                    }
                    if (!empty($this->subpart)) {

                        $url .= '/subpart:'.$this->subpart;

                    }
                    if (!empty($this->brand)) {

                        $url .= '__'.$this->brand;

                    }
                    if (!empty($this->args['search'])) {

                        $url .= ','.str_replace(' ', '+', $this->args['search']);
                        unset($this->args['search']);

                    }
                    if (!empty($this->args)) {

                        foreach($this->args AS $k => $v) {

                            $urlParams[] = $k.'='.$v;

                        }

                    }
                    if (!empty($urlParams)) {

                        $url .= '?'.implode('&', $urlParams);

                    }
                    $data = Api::data($url, $this->request);
                    $paginator = new PaginatorApi($data['total'], $data['page'], $data['slug']);
                    $data['paginator'] = $paginator->gets();
                    $data['filtersLabels'] = isset($data['elements']) ?
                        collect($data['elements'])->map(function($v, $k) use ($data) {
                            return '<li class="filters__labels__item" data-element="'.$k.'" data-value="'.$data['request'][$k].'"><span class="filter-label">'.$v.'<i class="fas fa-times"></i></li>';
                        })->join('') :
                        '';
                    $data['productsHTML'] = collect($data['products'])->map(function($product) {
                        return view(
                            'components.public.product',
                            array(
                                'product' => $product,
                                'isDesktop' => $this->isDesktop
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

                }
                $params = self::params($this->request->path());
                $elements['params'] = $params;
                $elements['orderBy'] = $this->request->has('orderBy') ? $this->request->get('orderBy') : 'code';
                $elements['type'] = $this->request->has('type') ? $this->request->get('type') : null;
                $elements['currentPage'] = $this->request->has('page') ? $this->request->get('page') : '1';
                $elements['args'] = $this->args;
                $elements['lateral'] = Family::gets();
                if (session()->has('markup')) {

                    $elements['markup'] = session()->get('markup');

                }
                break;
            case "producto":
                if ($this->return == 'api') {
                    $url = 'http://'.config('app.api').'/products';
                    $url .= '/'.$this->args['code'];
                    $url .= '/'.$this->args['type'];
                    if (!empty($this->args['userId'])) {

                        $url .= '/'.$this->args['userId'];

                    }
                    $data = Api::data($url, $this->request);
                    return $data;
                }
                $url = "http://".config('app.api').$_SERVER['REQUEST_URI'];
                $url = str_replace("producto:", "products/", $url);
                $data = Api::data($url, $this->request);
                $data['productsHTML'] = collect($data['products'])->map(function($product) {
                    return view('components.public.oneProduct', ['product' => $product])->render();
                })->join('');
                $elements['description'] = $data['products'][0]['name'];
                $elements['elements'] = $data;
                $elements['elements']['part'] = Part::where('name', $elements['elements']['products'][0]['part']['name'])->first()->family;
                $elements['elements']['subpart'] = Subpart::where('name', $elements['elements']['products'][0]['subpart']['name'])->first();
                $elements['lateral'] = Family::gets();
                break;
            case "pedido":
                $url = "http://".config('app.api').$_SERVER['REQUEST_URI'];
                $url = str_replace("pedido/parte:", "part:", $url);
                $url = str_replace("pedido", "products", $url);
                $url = str_replace("subparte:", "subpart:", $url);
                if ($pdf) {
                    if (str_contains($url, '?')) {
                        $url .= '&pdf=1';
                    } else {
                        $url .= '?pdf=1';
                    }
                }
                $data = Api::data($url, $this->request);
                if (empty($data) || $pdf) {
                    $elements = $data;
                    break;
                }
                if ($this->request->has('only') && $this->request->get('only') == 'products') {
                    $view = "";
                    $cart = [];
                    if (\auth()->guard('web')->check()) {
                        $cart = Cart::show($this->request);
                    }
                    foreach($data["products"] AS $element) {
                        $view .= view('page.mobile.__product')->with([
                            'product' => $element,
                            'cart' => $cart
                        ])->render();
                    }
                    echo empty($view) ? null : $view;die;
                }
                if (isset($data["part"]))
                    session(['part_pdf' => $data["part"]["name_slug"]]);
                else {
                    if (session()->has('part_pdf'))
                        session()->forget('part_pdf');
                }
                if (isset($data["subpart"]))
                    session(['subpart_pdf' => $data["subpart"]["name_slug"]]);
                else {
                    if (session()->has('subpart_pdf'))
                        session()->forget('subpart_pdf');
                }
                if (isset($data["brand"]))
                    session(['brand_pdf' => $data["brand"]]);
                else {
                    if (session()->has('brand_pdf'))
                        session()->forget('brand_pdf');
                }
                if (isset($data["search"]))
                    session(['search_pdf' => $data["search"]]);
                else {
                    if (session()->has('search_pdf'))
                        session()->forget('search_pdf');
                }
                if (!$pdf) {
                    $pageName = 'page';
                    $page = Paginator::resolveCurrentPage($pageName);
                    $data["products"] =  new LengthAwarePaginator($data["products"], $data["total"], $perPage = 36, $page, [
                        'path' => Paginator::resolveCurrentPath(),
                        'pageName' => $pageName,
                    ]);
                    $elements["lateral"] = Family::gets();
                    $elements["elements"] = $data;
                }
                
                break;
            case "mispedidos":
                $user = session()->has('accessADM') ? session()->get('accessADM') : auth()->guard('web')->user();
                $client = $user->getClient();
                $elements["orders"] = Order::data($this->request, configs("PAGINADO"), $client);
                break;
        }
        if (\auth()->guard('web')->check() && !$pdf) {
            $elements["cart"] = Cart::show($this->request);
        }
        return $elements;
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
