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
        switch($this->page) {
            case "parte":
                $args = [];
                if (session()->has('part_pdf')) {
                    $args[] = session()->get('part_pdf');
                    if (session()->has('subpart_pdf'))
                        $args[] = session()->get('subpart_pdf');
                } else
                    $args[] = null;
                $search = null;
                if (session()->has('brand_pdf'))
                    $args[] = session()->get('brand_pdf');
                if (session()->has('search_pdf'))
                    $search = session()->get('search_pdf');
                if (session()->has('subpart_pdf'))
                    $elements = Subpart::data($this->request, $args, 0, $search);
                else
                    $elements = Family::data($this->request, $args, 0, $search);
                break;
            case "subparte":
                $search = null;
                $args = [session()->get('part_pdf'), session()->get('subpart_pdf')];
                if (session()->has('brand_pdf'))
                    $args[] = session()->get('brand_pdf');
                if (session()->has('search_pdf'))
                    $search = session()->get('search_pdf');
                $elements = Subpart::data($this->request, $args, 0, $search);
                break;
            case "pedido":
                $args = [];
                if (session()->has('part_pdf')) {
                    $args[] = session()->get('part_pdf');
                    if (session()->has('subpart_pdf'))
                        $args[] = session()->get('subpart_pdf');
                } else
                    $args[] = null;
                $search = null;
                if (session()->has('brand_pdf'))
                    $args[] = session()->get('brand_pdf');
                if (session()->has('search_pdf'))
                    $search = session()->get('search_pdf');
                if (session()->has('subpart_pdf'))
                    $elements = Subpart::data($this->request, $args, 0, $search);
                else
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
                $url = env("APP_API") . $_SERVER['REQUEST_URI'];
                $url = str_replace("pedido/parte:", "part:", $url);
                $url = str_replace("parte:", "part:", $url);
                $url = str_replace("pedido", "products", $url);
                $url = str_replace("subparte:", "subpart:", $url);
                $url = str_replace("productos,", "products,", $url);
                $data = Api::data($url, $this->request);
                if (empty($data)) {
                    $elements = $data;
                    break;
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
                $url = env("APP_API") . $_SERVER['REQUEST_URI'];
                $url = str_replace("producto:", "product/", $url) . "/name_slug";
                $data = Api::data($url, $this->request);
                if (empty($data)) {
                    $elements = $data;
                    break;
                }
                $elements["description"] = $data["product"]["name"];
                $elements["elements"] = $data;
                $elements["elements"]["part"] = Part::where("name", $elements["elements"]["product"]["part"]["name"])->first()->family;
                $elements["elements"]["subpart"] = Subpart::where("name", $elements["elements"]["product"]["subpart"]["name"])->first();
                $elements["lateral"] = Family::gets();
                break;
            case "pedido":
                $url = env("APP_API") . $_SERVER['REQUEST_URI'];
                $url = str_replace("pedido/parte:", "part:", $url);
                $url = str_replace("pedido", "products", $url);
                $url = str_replace("subparte:", "subpart:", $url);
                $data = Api::data($url, $this->request);
                if (empty($data)) {
                    $elements = $data;
                    break;
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
                $user = auth()->guard('web')->user();
                $client = $user->getClient();
                $elements["orders"] = Order::data($this->request, configs("PAGINADO"), $client);
                break;
        }
        return $elements;
    }
}
