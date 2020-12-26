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
        $data = $site->elements();
        return view('page.base', compact('data'));
    }

    public function part(Request $request, $part)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function part_brand(Request $request, $part, $brand)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setBrand($brand);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function subpart(Request $request, $part, $subpart)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function subpart_brand(Request $request, $part, $subpart, $brand)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        $site->setBrand($brand);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
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

    public function redirect(Request $request)
    {
        $requestData = $request->except(['_token']);
        $search = $requestData["search"];
        $route = empty($requestData["brand"]) ? $requestData["route"] : $requestData["route"] . "_brand";
        unset($requestData["search"]);
        unset($requestData["route"]);
        if(empty($request->search) && empty($request->brand))
            return back()->withErrors(['password' => "Ingrese valores de búsqueda"])->withInput();
        return \Redirect::route($route, $requestData);
    }
}
