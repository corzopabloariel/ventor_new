<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Product;

class GeneralExportXLS implements FromView, WithTitle, ShouldAutoSize
{

    public function title(): string
    {
        return 'LISTAS DE PRECIOS';
    }

    public function view(): View
    {
        $products = Product::orderBy('stmpdh_art', 'ASC')->get();

        return view('exports.products.xls', [
            'products' => $products
        ]);
    }
}
