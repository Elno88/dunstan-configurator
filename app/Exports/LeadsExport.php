<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $date;

    public function forDate(string $date = null)
    {
        $this->date = $date;

        return $this;
    }

    public function query()
    {
        return Lead::query()
            ->when(empty($this->date), function ($query) {
                $query->notExported();
            })
            ->when(!empty($this->date), function ($query) {
                $query->whereDate('created_at', $this->date);
            })
            ->orderBy('ssn');
    }

    public function map($lead): array
    {
        return [
            $lead->created_at,
            $lead->name,
            substr_replace($lead->ssn, '-', 8, 0),
            $lead->address,
            $lead->zip,
            $lead->city,
            $lead->animal_name,
            $lead->animal_breed,
            $lead->animal_gender,
            $lead->animal_chip_number,
            $lead->animal_birth ? $lead->animal_birth->toDateString() : null,
            $lead->insurance_company,
            $lead->insurance_name,
            $lead->veterinary_amount,
            $lead->veterinary_remaining,
            $lead->animal_price,
            $lead->started_at ? $lead->started_at->toDateString() : null,
            $lead->renewal_at ? $lead->renewal_at->toDateString() : null,
            $lead->premium_method,
            $lead->premium_frequency,
            $lead->premium_amount,
        ];
    }

    public function headings(): array
    {
        return [
            'Datum/Tid',
            'Namn (försäkringstagare)',
            'Personnummer',
            'Adress',
            'Postnummer',
            'Stad',
            'Namn (häst)',
            'Ras',
            'Kön',
            'Chipnummer',
            'Födelsedatum',
            'Försäkringsbolag',
            'Försäkringsnamn',
            'Veterinärvårdsbelopp',
            'Veterinärvårdsbelopp (resterande)',
            'Livvärde',
            'Startdatum',
            'Förnyelsedatum',
            'Betalsätt',
            'Betaltermin',
            'Årspremie',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true
                ]
            ],
        ];
    }
}
