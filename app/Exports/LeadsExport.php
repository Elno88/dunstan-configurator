<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Lead::query()->notExported();
    }

    public function headings(): array
    {
        return [
            '#',
            'User',
            'Date',
        ];
    }
}
