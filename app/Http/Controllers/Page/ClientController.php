<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Client;
use App\Models\UserNotice;
use Jenssegers\Agent\Agent;

class ClientController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->agent = new Agent();
    }
    public function pedidos(Request $request)
    {
        $site = new Site("mispedidos");
        $site->setRequest($request);
        $data = $site->elements();
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }

    public function datos(Request $request)
    {
        $user = \Auth::user();
        if (!$user->isShowData() && !session()->has('accessADM')) {
            return \Redirect::route('index');
        }
        $site = new Site("misdatos");
        $site->setRequest($request);
        $data = $site->elements();
        $data["client"] = session()->has('accessADM') ? session()->get('accessADM')->getClient() : $user->getClient();

        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }

    public function action(Request $request, String $cliente_action)
    {
        $user = \Auth::user();
        if ($cliente_action == "mis-pedidos")
            return self::pedidos($request);
        if ($user->test) {
            return \Redirect::route('index');
        }
        if ($cliente_action == "mis-datos")
            return self::datos($request);
        $site = new Site("client");
        $site->setRequest($request);
        $data = $site->elements();
        if ($request->session()->has('nrocta')) {
            $client = Client::one($request->session()->get('nrocta'), "nrocta");
            $request->session()->forget('nrocta');
        } else {
            $user = session()->has('accessADM') ? session()->get('accessADM') : \Auth::user();
            $client = $user->getClient();
        }
        $data["client"] = $client;
        $data["action"] = $cliente_action;
        if (auth()->guard('web')->check() && !session()->has('accessADM')) {
            if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                $data["clients"] = Client::getAll("nrocta");
            if (auth()->guard('web')->user()->role == "VND") {
                if (empty(!auth()->guard('web')->user()->dockets))
                    $data["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->docket);
                else
                    $data["clients"] = Client::whereIn("vendedor.code", auth()->guard('web')->user()->dockets)->orderBy("nrocta", "ASC")->get();
            }
        }
        if (!empty($data["client"])) {
            $soap = $data["client"]->soap($cliente_action);
            $data["soap"] = $soap["soap"];
            $data["title"] = $soap["title"];
        }
        return view($this->agent->isDesktop() ? 'page.base' : 'page.mobile', compact('data'));
    }

    public function event(Request $request) {
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");
        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->notice) {

                $notice = $user->notice;
                $notice->fill(['read' => true]);
                $notice->save();
                $json = json_encode($notice->data);
                echo "id: ".$notice->id.PHP_EOL;
                echo "data: ".$json.PHP_EOL;
                echo "event: eventClient".PHP_EOL;
                echo PHP_EOL;
                flush();
                die;

            }

        }
        echo "id: 0".PHP_EOL;
        echo "data: []".PHP_EOL;
        echo "event: eventClient".PHP_EOL;
        echo PHP_EOL;
        flush();
    }
}
