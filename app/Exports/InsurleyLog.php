<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class InsurleyLog extends StringValueBinder implements FromView, WithStyles, ShouldAutoSize, WithCustomValueBinder
{
    private $insurley_rows = [];

    public function  __construct($insurley_rows)
    {
        $this->insurley_rows = $insurley_rows;
    }

    public function view(): View
    {
        return view('exports.insurley_log', [
            'insurley_rows' => $this->insurley_rows
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }

}
