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

class BasicController extends Controller
{

    public function soap(Request $request) {

        return Product::soap($request->use);

    }
    public function index(Request $request, string $link = "home") {

        $site = new Site($link);
        $site->setRequest($request);
        $data = $site->elements();
        if (empty($data)) {

            return \Redirect::route('index');

        }
        return view('page.base', compact('data'));

    }
    /////////////////
    public function data(Request $request) {

        $user = session()->has('accessADM') ? User::find(session()->get('accessADM')) : \Auth::user();
        $site = new Site('data');
        $site->setRequest($request);
        $site->setRoute($request->route);
        $site->setUser($user);
        $data = $site->api();
        return $data;

    }
    public function descargas(Request $request) {

        if ($request->has('id')) {

            $id = $request->id;
            $index = $request->index;
            return Download::track($id, $index);

        }
        return Download::limit();

    }
    public function client(Request $request, $cliente_action) {
        
        $site = new Site("client");
        $site->setArgs(
            array('action' => $cliente_action)
        );
        $site->setRequest($request);
        $data = $site->elements();
        if (empty($data['view'])) {

            return redirect()->route('index');

        }

        return view('page.base', compact('data'));

    }
    public function atencion(Request $request, $section) {

        $site = new Site($section);
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));

    }
    public function product(Request $request, $product) {

        $site = new Site('producto');
        $site->setArgs(
            array('code' => $product)
        );
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));

    }
    public function feed(Request $request) {

        $products = Product::orderBy('use', 'ASC')->get();
        $output = \View::make('xmlProducts')->with(compact('products'))->render();
        echo $output;

    }

    public function feedFile(String $file, String $hash = null, String $ext = null) {

        return Hashfile::search($file, $hash, $ext);

    }

    public function part(Request $request) {

        $site = new Site('parte');
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));

    }

    public function application(Request $request, $data = null) {

        $site = new Site('aplicacion');
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));

    }

}
