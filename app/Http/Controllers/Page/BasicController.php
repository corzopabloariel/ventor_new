<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ventor\Site;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use App\Models\Family;
use App\Models\Product;
use App\Models\Ventor\Download;
use App\Models\Ventor\DownloadUser;
use App\Models\Ventor\Api;
use PDF;
use Jenssegers\Agent\Agent;

class BasicController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function create_pdf(Request $request, $data)
    {
        $data["colors"] = Family::colors();
        return view('page.pdf', $data);
    }

    public function index(Request $request, $link = "home")
    {
        if (!$request->secure()) {
            /*$url = str_replace("http:", "https:", $request->getSchemeAndHttpHost() . $request->getRequestUri());
            return redirect()->to($url);*/
        }
        $site = new Site($link);
        $site->setRequest($request);
        $data = $site->elements();
        if (empty($data))
            return \Redirect::route('index');
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }

    public function products(Request $request, $search, $brand = null)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setSearch($search);
        if (!empty($brand))
            $site->setBrand($brand);
        if ($request->method() == "GET") {
            $data = $site->elements();
            if (empty($data))
                return \Redirect::route('index');
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
        }
        return self::create_pdf($request, $site->pdf());
    }

    public function part(Request $request, ...$args)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        if ($request->method() == "GET") {
            $data = $site->elements();
            if (empty($data))
                return \Redirect::route('index');
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
        }
        return self::create_pdf($request, $site->pdf());
    }

    public function product(Request $request, $product)
    {
        $site = new Site("producto");
        $site->setRequest($request);
        $site->setProduct($product);
        if ($request->method() == "GET") {
            $data = $site->elements();
            if (empty($data))
                return \Redirect::route('index');
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
        }
        return self::create_pdf($request, $site->pdf());
    }

    public function order(Request $request, ...$args)
    {
        $products = Cart::products($request);
        $site = new Site("pedido");
        $site->setRequest($request);
        if ($request->method() == "GET") {
            $data = $site->elements();
            if (empty($data))
                return \Redirect::route('index');
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
        }
        return self::create_pdf($request, $site->pdf());
    }

    public function redirect(Request $request)
    {
        $requestData = $request->except(['_token']);
        $route = $requestData["route"];
        if (isset($requestData["part"]))
            $route .= "_part";
        if (isset($requestData["subpart"]))
            $route .= "_subpart";
        if (!empty($requestData["brand"]))
            $route .= "_brand";
        if (empty($requestData["search"]))
            unset($requestData["search"]);
        else {
            //$search = Str::slug($requestData["search"], "_");
            $search = str_replace(" ", "_", trim($requestData["search"]));
            session(['search' => [$search => $requestData["search"]]]);
            $requestData["search"] = $search;
            $route .= "_search";
        }
        unset($requestData["route"]);
        if(empty($request->search) && empty($request->brand))
            return back()->withErrors(['password' => "Ingrese valores de búsqueda"])->withInput();
        return \Redirect::route($route, $requestData);
    }

    /////////////////

    public function data(Request $request, $attr)
    {
        $user = session()->has('accessADM') ? session()->get('accessADM') : \auth()->guard('web')->user();
        $data = [];
        switch ($attr) {
            case "dates":
                $data["start"] = $request->datestart;
                $data["end"] = $request->dateend;
                break;
            case "markup":
                $data["discount"] = $request->markup;
                break;
        }
        $user->history($data);
        $user->fill($data);
        $user->save();
        return redirect()
            ->back()
            ->with('success', 'Datos modificados');
    }

    public function type(Request $request)
    {
        if ($request->has("darkmode")) {
            \Auth::user()->setConfig([
                'dark_mode' => !$request->get("status")
            ]);
            return response()->json([
                "error" => 0,
                "status" => $request->get("status"),
                "success" => true
            ], 200);
        }
        if ($request->has("markup")) {
            if ($request->session()->has('markup')) {
                session(['markup' => $request->type]);
            } else {
                session(['markup' => $request->type]);
            }
        } else {
            if ($request->session()->has('type')) {
                $type = $request->session()->get('type');
                if ($type == $request->filter)
                    $request->session()->forget('type');
                else
                    session(['type' => $request->filter]);
            } else {
                session(['type' => $request->filter]);
            }
        }
        return response()->json([
            "error" => 0,
            "success" => true
        ], 200);
    }

    public function soap(Request $request)
    {
        return Product::soap($request->use);
    }

    public function track_download(Request $request, Download $download)
    {

        return $download->track();

    }

    public function atencion(Request $request, $section)
    {
        $site = new Site($section);
        $site->setRequest($request);
        $data = $site->elements();
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }
}
