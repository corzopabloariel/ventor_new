<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Order;
use App\Models\Transport;
use App\Models\User;
use App\Http\Resources\OrderCompleteResource;

class OrderController extends Controller
{
    public function index(Request $request) {

        return Order::gets($request);

    }
    public function store(Request $request) {

        return Order::element($request);

    }
    public function show(Request $request, String $order) {

        return Order::one($request, $order);

    }
    public function export(Request $request, String $order) {

        $order = Order::find($order);
        $file = $order->export('PEDIDO-'.$order->id.'.xls');
        return file_get_contents($file['file']);

    }
    public function products(Request $request, int $order) {

        $order = Order::find($order);
        return new OrderCompleteResource($order);

    }
}
