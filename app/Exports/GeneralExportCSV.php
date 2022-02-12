<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GeneralExportCSV implements FromView
{
    public function view(): View
    {
        $products = Product::where('precio', '>', 0)->orderBy('stmpdh_art', 'ASC')->get();

        return view('exports.products.csv', [
            'products' => $products
        ]);
    }
}
