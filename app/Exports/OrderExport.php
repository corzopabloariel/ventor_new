<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Order;

class OrderExport implements FromView
{
    protected $_id;
    function __construct($_id) {
        $this->_id = $_id;
    }

    public function view(): View
    {
        $order_id = $this->_id;
        $order = Order::where("_id", $order_id)->first();

        $o = collect($order->products)->map(function($item) use ($order) {
            return [
                'exp_1' => 'MN',
                'exp_2' => '',
                'cod' => $item['product']['code'],
                'exp_4' => '',
                'cnt' => $item['quantity'],
                'precio' => $item['product']['priceNumber'],
                'bonif1' => '',
                'bonif2' => '',
                'observ' => '',
                'cliente' => isset($order['client']['nrocta']) ? $order['client']['nrocta'] : 'PRUEBA',
                'destrp' => $order['transport']['description'],
                'dirtrp' => $order['transport']['address'],
                'idpedido' => $order->uid
            ];
        })->toArray();
    
        return view('exports.order', [
            'orders' => $o
        ]);
    }
}
