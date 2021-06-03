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
        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
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
            if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            return view('page.mobile', compact('data'));
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
            if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            return view('page.mobile', compact('data'));
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
            if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            return view('page.mobile', compact('data'));
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
            if ($this->agent->isDesktop())
                return view('page.base', compact('data'));
            return view('page.mobile', compact('data'));
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
        $msserver="181.170.160.91:9090";

        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

        $param = array( "pSPName" => "ConsultaStock", "pParamList" => '$ARTCOD;' . $request->use, "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl');
            $result = $client->call('EjecutarSP_String', $param, '', '', false, true);
            if ($client->fault) {
                return -1;
            } else {
                $err = $client->getError();
                if ($err)
                    return -2;
                else {
                    $cadena = explode(",", $result["EjecutarSP_StringResult"]);
                    if ($cadena[2] > 0 )
                        return $cadena[2];
                    else
                        return $cadena[2];
                }
            }
        } catch (\Throwable $th) {
            return -3;
        }
    }

    public function track_download(Request $request, Download $download)
    {
        if (\Auth::check()) {
            $flag = true;
            $dateStart = date("Y-m-d H:i:s", strtotime("-1 hour"));
            $dateEnd = date("Y-m-d H:i:s");
            $user = \Auth::user();
            if ($user->limit != 0) {
                if ($user->downloads->count() != 0) {
                    if ($user->limit <= $user->downloads->whereBetween("created_at", [$dateStart, $dateEnd])->count()) {
                        return response()->json([
                            "error" => 1,
                            "msg" => 'Llego al límite de descargas por hora'
                        ], 200);
                    }
                }
                DownloadUser::create(["download_id" => $download->id, "user_id" => $user->id]);
            }
            if ($flag) {
                return response()->json([
                    "error" => 0,
                    "success" => true
                ], 200);
            }
        }
        return response()->json([
            "error" => 1,
            "msg" => 'Ingrese a su cuenta para poder acceder a los archivos'
        ], 200);
    }

    public function atencion(Request $request, $section)
    {
        $site = new Site($section);
        $site->setRequest($request);
        $data = $site->elements();
        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
    }
}
