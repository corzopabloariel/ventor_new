<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
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
use App\Models\Hashfile;
use App\Models\User;
use PDF;
use Jenssegers\Agent\Agent;

class BasicController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function pdf(Request $request) {
        $data = array(
            'html' => $request->html,
            'title' => $request->title
        );
        return view('exports.pdf', $data);
    }

    public function create_pdf(Request $request, $data)
    {
        $data["colors"] = Family::colors();
        //return view('page.pdf', $data);
        return \PDF::loadView('page.pdf', $data)
            ->download(config('app.name').'.pdf');
    }

    /**
     * OK
     */
    public function index(Request $request, string $link = "home") {

        $site = new Site($link);
        $site->setRequest($request);
        $data = $site->elements();
        if (empty($data)) {

            return \Redirect::route('index');

        }
        return view('page.base', compact('data'));

    }

    /// Deprecado
    /*public function products(Request $request, $search, $brand = null)
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
    }*/

    public function application(Request $request, $data = null) {
        if ($request->method() == 'POST') {
            $site = new Site("aplicacion");
            $site->setRequest($request);
            return $site->modal();
        }
        list($type, $args) = explode(':', $data);
        $args = explode(',', $args);
        $site = new Site("aplicacion");
        $site->setRequest($request);
        $site->setArgs($args);
        if ($request->method() == 'GET' && $type == '_json') {
            $site->setReturn('json');
            $data = $site->elements();
            return $data;
        }
        if ($request->method() == 'GET' && $type == '') {
            $data = $site->elements();
            return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
        }
    }

    /**
     * OK
     */
    public function part(Request $request, ...$args) {

        $site = new Site("parte");
        $path = $request->path();
        $site->setArgs($args);
        $site->setRequest($request);
        if ($request->method() == "GET") {

            $data = $site->elements();
            if (empty($data)) {

                return \Redirect::route('index');

            }
            return view('page.base', compact('data'));

        }
        return self::create_pdf($request, $site->pdf());

    }
    /**
     * OK
     */
    public function product(Request $request, $product) {

        $args = array(
            'code'      => $product,
            'type'      => 'data'
        );
        $site = new Site("producto");
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('data');
        $data = $site->elements();
        return view('page.base', compact('data'));

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

    //public function orderProduct(Request $request, )

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
            $search = str_replace(" ", "+", trim($requestData["search"]));
            $requestData["search"] = $search;
            $route .= "_search";
        }
        unset($requestData["route"]);
        if(empty($request->search) && empty($request->brand))
            return back()->withErrors(['password' => "Ingrese valores de búsqueda"])->withInput();
        return \Redirect::route($route, $requestData);
    }

    /////////////////
    public function data(Request $request) {

        $user = session()->has('accessADM') ? session()->get('accessADM') : \auth()->guard('web')->user();
        $site = new Site('data');
        $site->setRequest($request);
        $site->setRoute($request->route);
        $site->setUser($user);
        $data = $site->api();
        return $data;

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
        if ($request->has("cartSelect")) {
            session(['cartSelect' => $request->cartSelect]);
        }
        if ($request->has("messageTab")) {
            try {
                $config = \Auth::user()->config;
                $other = array();
                if ($config) {
                    $other = $config->other;
                    if (empty($other))
                        $other = array();
                }
                $other['messageTab'] = 1;
                \Auth::user()->setConfig([
                    'other' => $other
                ]);
                return responseReturn(false, 'Preferencia guardada');
            } catch (\Throwable $th) {
                return responseReturn(false, 'Ocurrió un error', 1);
            }
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

    public function descargas(Request $request) {

        if ($request->has('id')) {

            $id = $request->id;
            $index = $request->index;
            return Download::track($id, $index);

        }
        return Download::limit();

    }

    public function atencion(Request $request, $section)
    {
        $site = new Site($section);
        $site->setRequest($request);
        $data = $site->elements();
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }

    public function feed(Request $request) {
        $products = Product::orderBy('use', 'ASC')->get();
        $output = \View::make('xmlProducts')->with(compact('products'))->render();
        echo $output;
    }

    public function feedFile(String $file, String $hash = null, String $ext = null) {
        return Hashfile::search($file, $hash, $ext);
    }
}
