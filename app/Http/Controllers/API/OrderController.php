<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Order;
use App\Models\Transport;
use App\Models\User;

class OrderController extends Controller
{
    public function index(Request $request) {

        if($request->isJson()) {

            return Order::gets($request);

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }

    }
    public function store(Request $request) {

        return Order::element($request);

    }
    public function show(Request $request, String $order) {

        if($request->isJson()) {

            return Order::one($request, $order);

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }

    }
}
