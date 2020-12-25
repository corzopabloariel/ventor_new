<?php

namespace App\Models\Ventor;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ventor\Slider;
use App\Models\Ventor\Newness;
use App\Models\Content;
use App\Models\Family;
use App\Models\Subpart;
use App\Models\Product;

class Site
{
    private String $page, $part, $subpart, $product, $brand;
    private Request $request;
    function __construct($page) {
        $this->page = $page;
        $this->subpart = "";
        $this->product = "";
        $this->brand = "";
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

    public function elements() {
        $elements = [
            "sliders" => self::slider(),
            "content" => self::content(),
        ];
        switch($this->page) {
            case "home":
                $elements["newness"] = Newness::gets();
                $elements["families"] = Family::gets();
                break;
            case "descargas":
                $elements["downloads"] = Download::gets();
                break;
            case "productos":
                $elements["families"] = Family::gets();
                break;
            case "parte":
                $args = [$this->part];
                if (!empty($this->brand))
                    $args[] = $this->brand;
                $elements["elements"] = Family::data($this->request, $args, env('PAGINATE'));
                if ($elements["elements"]["products"]->isNotEmpty())
                    $elements["total"] = $elements["elements"]["products"]->total();
                break;
            case "subparte":
                $args = [$this->part, $this->subpart];
                if (!empty($this->brand))
                    $args[] = $this->brand;
                $elements["elements"] = Subpart::data($this->request, $args, env('PAGINATE'));
                if ($elements["elements"]["products"]->isNotEmpty())
                    $elements["total"] = $elements["elements"]["products"]->total();
                break;
            case "producto":
                $elements["product"] = Product::one($this->product, "name_slug");
                $codigo_ima = $elements["product"]["codigo_ima"];
                $name = "IMAGEN/{$codigo_ima[0]}/{$codigo_ima}";
                $images = ["{$name}.jpg"];
                for ($i = 1; $i <= 10; $i++) {
                    if (file_exists(public_path() . "{$name}-{$i}.jpg"))
                        $images[] = "{$name}-{$i}.jpg";
                }
                $elements["product"]["images"] = $images;
                break;
            case "pedido":
                $products = Product::orderBy("parte")
                    ->orderBy("subparte.code")
                    ->orderBy("web_marcas");
                $marcas = collect(Product::select('web_marcas')
                    ->distinct()
                    ->get())
                        ->unique()
                        ->toArray();
                $marcas = collect($marcas)->map(function ($item, $key) {
                    return ["name" => $item[0], "slug" => Str::slug($item[0])];
                })->toArray();
                $elements["elements"]["products"] = $products->paginate((int)env('PAGINATE'));
                $elements["elements"]["brand"] = $marcas;
                break;
        }
        return $elements;
    }
}
