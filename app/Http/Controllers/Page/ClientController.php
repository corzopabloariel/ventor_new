<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Client;
use App\Models\UserNotice;
use App\Models\User;

class ClientController extends Controller
{
    public function pedidos(Request $request)
    {
        $site = new Site("mispedidos");
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));
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

        return view('page.base', compact('data'));
    }

    public function action(Request $request, String $cliente_action) {

        $site = new Site("client");
        $site->setArgs(
            array('action' => $cliente_action)
        );
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));

    }

    public function browser(Request $request) {
        $user = User::where('username', $request->username)->first();
        if ($request->has('reload')) {
            $user->addNotice(['message' => $request->has('message') ? $request->get('message') : 'Espere, se recargará la página', 'action' => 'reload']);
            return responseReturn(false, 'Se notificó al cliente');
        }
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
