<?php

namespace App\Models\Ventor;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ventor\Slider;
use App\Models\Ventor\Newness;
use App\Models\Content;
use App\Models\Family;
use App\Models\Client;
use App\Models\Part;
use App\Models\Subpart;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transport;

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

    public function elements() {
        $elements = [
            "page" => $this->page,
            "sliders" => self::slider(),
            "content" => self::content(),
        ];

        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                $elements["clients"] = Client::getAll("nrocta");
            if (auth()->guard('web')->user()->role == "VND")
                $elements["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->docket);
        }
        switch($this->page) {
            case "home":
                $elements["newness"] = Newness::gets();
                $elements["families"] = Family::gets();
                break;
            case "descargas":
                $elements["order"] = Content::section("categoriesDownload")->data;
                $elements["downloads"] = Download::gets();
                break;
            case "productos":
                $elements["families"] = Family::gets();
                break;
            case "checkout":
                if (session()->has('nrocta_client')) {
                    $elements["client"] = Client::one(session()->get('nrocta_client'), "nrocta");
                    $elements["transport"] = Transport::gets($elements["client"]->_id ?? "");
                } else
                    $elements["transport"] = Transport::gets(\auth()->guard('web')->user()->uid ?? "");
                break;
            case "parte":
                $args = [];
                if (!empty($this->part)) {
                    $args[] = $this->part;
                    $elements["part"] = Family::where("name_slug", $this->part)->first();
                } else
                    $args[] = null;
                $search = null;
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                    $elements["brand"] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                    $elements["search"] = $this->request->session()->has('search') ?
                        (isset($this->request->session()->get('search')[$this->search]) ?
                            $this->request->session()->get('search')[$this->search] : str_replace("_", " ", $this->search)) : "";
                }
                $elements["lateral"] = Family::gets();
                $elements["elements"] = Family::data($this->request, $args, configs("PAGINADO"), $search);
                if ($elements["elements"]["products"]->isNotEmpty())
                    $elements["total"] = $elements["elements"]["products"]->total();
                break;
            case "subparte":
                $search = null;
                $args = [$this->part, $this->subpart];
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                    $elements["brand"] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                    $elements["search"] = $this->request->session()->has('search') ?
                        (isset($this->request->session()->get('search')[$this->search]) ?
                            $this->request->session()->get('search')[$this->search] : str_replace("_", " ", $this->search)) : "";
                }
                $elements["part"] = Family::where("name_slug", $this->part)->first();
                $elements["subpart"] = Subpart::where("name_slug", $this->subpart)->first();
                $elements["lateral"] = Family::gets();
                $elements["elements"] = Subpart::data($this->request, $args, configs("PAGINADO"), $search);
                if ($elements["elements"]["products"]->isNotEmpty())
                    $elements["total"] = $elements["elements"]["products"]->total();
                break;
            case "producto":
                $elements["product"] = Product::one($this->product, "name_slug");
                $elements["part"] = Part::where("name", $elements["product"]["parte"])->first()->family;
                $elements["subpart"] = Subpart::where("name", $elements["product"]["subparte"]["name"])->where("code", $elements["product"]["subparte"]["code"])->first();
                $elements["lateral"] = Family::gets();
                break;
            case "pedido":
                $args = [];
                if (!empty($this->part)) {
                    $args[] = $this->part;
                    $elements["part"] = Family::where("name_slug", $this->part)->first();
                } else
                    $args[] = null;
                $search = null;
                if (!empty($this->brand)) {
                    $args[] = $this->brand;
                    $elements["brand"] = $this->brand;
                }
                if (!empty($this->search)) {
                    $search = $this->search;
                    $elements["search"] = $this->request->session()->has('search') ?
                        (isset($this->request->session()->get('search')[$this->search]) ?
                            $this->request->session()->get('search')[$this->search] : str_replace("_", " ", $this->search)) : "";
                }
                $elements["lateral"] = Family::gets();
                $elements["elements"] = Family::data($this->request, $args, configs("PAGINADO"), $search);
                
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
