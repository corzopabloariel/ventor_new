<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Product;

class GeneralExportXLS implements FromView, WithColumnFormatting, WithTitle, ShouldAutoSize
{

    public function title(): string
    {
        return 'LISTAS DE PRECIOS';
    }

    public function view(): View
    {
        $products = Product::where('precio', '>', 0)->orderBy('stmpdh_art', 'ASC')->get();

        return view('exports.products.xls', [
            'products' => $products
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@'
        ];
    }
}
