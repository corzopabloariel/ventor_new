<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    protected $order;
    function __construct($rows) {
        $this->rows = $rows;
    }

    public function view(): View
    {

        return view('exports.order', [
            'orders' => $this->rows
        ]);

    }
}
