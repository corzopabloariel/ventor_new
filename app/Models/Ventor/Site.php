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

use App\Models\Ventor\Api;

class Site
{
    private String $page, $part, $subpart, $product, $brand, $search;
    private Request $request;
    function __construct($page) {
        $this->page = $page;
        $this->subpart = "";
        $this->product = "";
        $this->brand = "";
        $this->search = "";
    }

    public function setRequest(Request $request) {
        $this->request = $request;
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

    public function pdf() {
        self::elements(1);
        switch($this->page) {
            case "parte":
                $args = [];
                if (!empty($this->part)) {
                    $args[] = $this->part;
                } else
                    $args[] = null;
                $search = null;
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                }
                $elements = Family::data($this->request, $args, 0, $search);
                break;
            case "subparte":
                $search = null;
                $args = [$this->part, $this->subpart];
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                }
                $elements = Subpart::data($this->request, $args, 0, $search);
                break;
            case "pedido":
                $args = [];
                if (!empty($this->part)) {
                    $args[] = $this->part;
                } else
                    $args[] = null;
                $search = null;
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                }
                $elements = Family::data($this->request, $args, 0, $search);
                break;
        }
        return $elements;
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
                    if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                        $elements["clients"] = Client::getAll("nrocta");
                    if (auth()->guard('web')->user()->role == "VND")
                        $elements["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->docket);
                }
                break;
        }
        switch($this->page) {
            case "home":
                $elements["newness"] = Newness::gets();
                $elements["families"] = Family::gets();
                break;
            case "descargas":
                $elements["order"] = Content::section("categoriesDownload")->data;
                $elements["downloads"] = Download::gets();
                $elements["program"] = configs("LINK_PROGRAMA");
                break;
            case "productos":
                $elements["families"] = Family::gets();
                break;
            case "contacto":
                $elements["number"] = Number::orderBy("order")->get();
                break;
            case "pagos":
                $elements["banco"] = Text::where("name", "CUENTAS BANCARIAS")->first();
                $elements["pagos"] = Text::where("name", "PAGOS VIGENTES")->first();
                break;
            case "checkout":
                if (session()->has('nrocta_client')) {
                    $elements["client"] = Client::one(session()->get('nrocta_client'), "nrocta");
                    $elements["transport"] = Transport::gets($elements["client"]->_id ?? "");
                } else
                    $elements["transport"] = Transport::gets(\auth()->guard('web')->user()->uid ?? "");
                break;
            case "parte":
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                    $url = "https://";
                else
                    $url = "http://";
                $url.= env("APP_URL") . "api".$_SERVER['REQUEST_URI'];
                $url = str_replace("pedido/parte:", "part:", $url);
                $url = str_replace("parte:", "part:", $url);
                $url = str_replace("pedido", "products", $url);
                $url = str_replace("subparte:", "subpart:", $url);
                $url = str_replace("productos,", "products,", $url);
                $data = Api::data($url, $this->request);
                if (empty($data))
                    return \Redirect::route('index');
                $pageName = 'page';
                $page = Paginator::resolveCurrentPage($pageName);
                $data["products"] =  new LengthAwarePaginator($data["products"], $data["total"], $perPage = 36, $page, [
                    'path' => Paginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]);
                $elements["lateral"] = Family::gets();
                $elements["elements"] = $data;
                break;
            case "producto":
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                    $url = "https://";
                else
                    $url = "http://";
                $url.= env("APP_URL") . "api".$_SERVER['REQUEST_URI'];
                $url = str_replace("producto:", "product/", $url);
                $data = Api::data($url, $this->request);
                if (empty($data))
                    return \Redirect::route('index');
                $elements["description"] = $data["product"]["name"];
                $elements["elements"] = $data;
                $elements["elements"]["part"] = Part::where("name", $elements["elements"]["product"]["part"]["name"])->first()->family;
                $elements["elements"]["subpart"] = Subpart::where("name", $elements["elements"]["product"]["subpart"]["name"])->first();
                $elements["lateral"] = Family::gets();
                break;
            case "pedido":
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                    $url = "https://";
                else
                    $url = "http://";
                $url.= env("APP_URL") . "api".$_SERVER['REQUEST_URI'];
                $url = str_replace("pedido/parte:", "part:", $url);
                $url = str_replace("pedido", "products", $url);
                $url = str_replace("subparte:", "subpart:", $url);
                $data = Api::data($url, $this->request);
                if (empty($data))
                    return \Redirect::route('index');
                if (isset($data["part"]))
                    self::setPart($data["part"]["name_slug"]);
                if (isset($data["subpart"]))
                    self::setSubPart($data["subpart"]["name_slug"]);
                if (isset($data["brand"]))
                    self::setBrand($data["brand"]);
                if (isset($data["search"]))
                    self::setSearch($data["search"]);
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
                $user = auth()->guard('web')->user();
                $client = $user->getClient();
                $elements["orders"] = Order::data($this->request, configs("PAGINADO"), $client);
                break;
        }
        return $elements;
    }
}
