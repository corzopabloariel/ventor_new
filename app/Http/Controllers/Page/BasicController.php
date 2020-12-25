<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Family;

class BasicController extends Controller
{
    public function index(Request $request, $link = "home")
    {
        $site = new Site($link);
        dd($site->elements());
    }

    public function part(Request $request, $part)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        dd($site->elements());
        //$part = Family::data($request, $part);
    }

    public function part_brand(Request $request, $part, $brand)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setBrand($brand);
        dd($site->elements());
        //$part = Family::data($request, $part);
    }

    public function subpart(Request $request, $part, $subpart)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        dd($site->elements());
    }

    public function subpart_brand(Request $request, $part, $subpart, $brand)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        $site->setBrand($brand);
        dd($site->elements());
    }

    public function product(Request $request, $product)
    {
        $site = new Site("producto");
        $site->setRequest($request);
        $site->setProduct($product);
        dd($site->elements());
    }

    public function order(Request $request)
    {
        $site = new Site("pedido");
        $site->setRequest($request);
        dd($site->elements());
    }
}
