<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function products(Request $request, $search, $brand = null)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setSearch($search);
        if (!empty($brand))
            $site->setBrand($brand);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function part(Request $request, $part, $search = null)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        if (!empty($search))
            $site->setSearch($search);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function part_brand(Request $request, $part, $brand, $search = null)
    {
        $site = new Site("parte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setBrand($brand);
        if (!empty($search))
            $site->setSearch($search);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function subpart(Request $request, $part, $subpart, $search = null)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        if (!empty($search))
            $site->setSearch($search);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function subpart_brand(Request $request, $part, $subpart, $brand, $search = null)
    {
        $site = new Site("subparte");
        $site->setRequest($request);
        $site->setPart($part);
        $site->setSubPart($subpart);
        $site->setBrand($brand);
        if (!empty($search))
            $site->setSearch($search);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function product(Request $request, $product)
    {
        $site = new Site("producto");
        $site->setRequest($request);
        $site->setProduct($product);
        $data = $site->elements();//dd($data);
        return view('page.base', compact('data'));
    }

    public function order(Request $request)
    {
        $site = new Site("pedido");
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));
    }

    public function redirect(Request $request)
    {
        $requestData = $request->except(['_token']);
        $route = $requestData["route"];
        if (!empty($requestData["brand"]))
            $route .= "_brand";
        if (empty($requestData["search"]))
            unset($requestData["search"]);
        else {
            $search = Str::slug($requestData["search"], "_");
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
        $user = \auth()->guard('web')->user();
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
}
