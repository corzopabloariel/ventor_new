<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Email;
use App\Models\Ventor\Ticket;
use App\Models\Ventor\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BaseMail;
use App\Models\Ventor\Site;
use App\Models\Ventor\PaginatorApi;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['read'])) {

            return redirect()->route('adm')->withErrors(['password' => 'No tiene permitido el acceso al listado de Clientes']);

        }
        $site = new Site('clients');
        $args = array(
            'admin'     => 1,
            'paginate'  => PAGINATE
        );
        $args = array_merge($args, $request->all());
        $site->setArgs($args);
        $site->setRequest($request);
        $site->setReturn('api');
        $data = $site->api();
        $slug = 'adm/clients';
        if ($request->has('search')) {

            $slug .= '?search='.$request->get('search');

        }
        $paginator = new PaginatorApi($data['total']['clients'], $data['total']['pages'], $data['page'], $slug);
        $data['paginator'] = $paginator->gets();
        $data['request'] = $request;
        $table = view(
            'admin.clients.table',
            $data
        )->render();
        $blade = view(
            'admin.clients',
            array(
                'table' => $table,
                'total' => $data['total']['clients'],
                'form'  => array(
                    'url'           => \URL::to(\Auth::user()->redirect().'/clients'),
                    'placeholder'   => 'Buscar en todos los campos',
                    'search'        => $request->has('search') ? $request->get('search') : null
                )
            )
        );
        return view('admin',
            array(
                'blade'     => $blade,
                'script'    => 'clients',
                'modal'     => 'clients'
            )
        );

    }


    public function load(Bool $fromCron = false) {

        if (\Auth::check()) {
            $permissions = \Auth::user()->permissions;
            if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['update'])) {
                return responseReturn(false, 'Acción no permitida', 1, 200);
            }
        }
        return Client::updateCollection($fromCron);

    }

    public function pass(Request $request, $client) {

        $permissions = \Auth::user()->permissions;
        if (!empty($permissions) && (!isset($permissions['clients']) || isset($permissions['clients']) && !$permissions['clients']['update'])) {

            return responseReturn(false, 'Acción no permitida', 1, 200);

        }
        $site = new Site('client');
        $args = array(
            'type'      => 'update',
            'update'    => 'password',
            'client'    => $client,
            'data'      => $request->all()
        );
        $requestNew = new \Illuminate\Http\Request();
        $requestNew->setMethod('PUT');
        $requestNew->request->add(['method' => 'PUT']);
        $site->setArgs($args);
        $site->setRequest($requestNew);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cart(Request $request) {

        $site = new Site('cart');
        $args = array(
            'admin'     => 1,
            'userId'    => $request->userId
        );
        $requestNew = new \Illuminate\Http\Request();
        $requestNew->setMethod('GET');
        $requestNew->request->add(['method' => 'GET']);
        $site->setArgs($args);
        $site->setRequest($requestNew);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }
    public function cartDelete(Request $request, $cart) {

        $site = new Site('cart');
        $args = array(
            'admin'     => 1,
            'cartId'    => $cart
        );
        $requestNew = new \Illuminate\Http\Request();
        $requestNew->setMethod('DELETE');
        $requestNew->request->add(['method' => 'DELETE']);
        $site->setArgs($args);
        $site->setRequest($requestNew);
        $site->setReturn('api');
        $data = $site->api();
        return $data;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function access(Request $request, Client $client)
    {
        if (!\Auth::user()->isAdmin()) {
            return responseReturn(false, 'Acción no permitida', 1);
        }
        try {
            if (session()->has('accessADM') && session()->get('accessADM')->uid == $client->_id) {
                if ($request->session()->has('markup')) {
                    $request->session()->forget('markup');
                }
                if ($request->session()->has('accessADM')) {
                    $request->session()->forget('accessADM');
                }
                if ($request->session()->has('type')) {
                    $request->session()->forget('type');
                }
                return \Redirect::route('index', ['link' => 'pedido']);
            }
            $user = $client->user();
            session(['accessADM' => $user]);
        } catch (\Throwable $th) {
            return responseReturn(false, 'Cliente no encontrado', 1);
        }
        return responseReturn(false, '');
    }
}
