<?php

namespace App\Imports;

use App\Models\Lead;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public $rows = 0;

    /**
     * @param array $row
     *
     * @return \App\Models\Lead|null
     */
    public function model(array $row)
    {
        $this->rows++;

        return new Lead([
            'external_id'          => uniqid(),
            'ssn'                  => $row['personnummer'],
            'name'                 => $row['namn_forsakringstagare'] ?? null,
            'address'              => $row['adress'] ?? null,
            'zip'                  => $row['postnummer'] ?? null,
            'city'                 => $row['stad'] ?? null,
            'animal_name'          => $row['namn_hast'] ?? null,
            'animal_breed'         => $row['ras'] ?? null,
            'animal_gender'        => $row['kon'] ?? null,
            'animal_chip_number'   => $row['chipnummer'] ?? null,
            'animal_birth'         => !empty($row['fodelsedatum']) ? Carbon::createFromFormat('d/m/Y', $row['fodelsedatum']) : null,
            'animal_price'         => $row['livvarde'] ?? 0,
            'insurance_company'    => $row['forsakringsbolag'] ?? null,
            'insurance_name'       => $row['forsakringsnamn'] ?? null,
            'insurance_type'       => null,
            'insurance_sub_type'   => null,
            'insurance_number'     => $row['forsakringsnummer'] ?? null,
            'premium_frequency'    => ($row['betaltermin'] > 0 && $row['betaltermin'] <= 12) ? $row['betaltermin'] : 12,
            'premium_amount'       => $row['arspremie'] ?? 0,
            'premium_method'       => $row['betalsatt'] ?? null,
            'veterinary_amount'    => $row['veterinarvardsbelopp'] ?? 0,
            'veterinary_remaining' => $row['veterinarvardsbelopp_resterande'] ?? 0,
            'coming'               => 0,
            'employer_paid'        => 0,
            'other'                => null,
            'data'                 => $row,
            'started_at'           => !empty($row['startdatum']) ? Carbon::createFromFormat('d/m/Y', $row['startdatum']) : null,
            'renewal_at'           => !empty($row['fornyelsedatum']) ? Carbon::createFromFormat('d/m/Y', $row['fornyelsedatum']) : null,
            'created_at'           => !empty($row['hamtningsdatum_inkl_tid']) ? Carbon::createFromFormat('d/m/Y H:i', $row['hamtningsdatum_inkl_tid']) : now(),
            'updated_at'           => !empty($row['hamtningsdatum_inkl_tid']) ? Carbon::createFromFormat('d/m/Y H:i', $row['hamtningsdatum_inkl_tid']) : now(),
            'exported_at'          => !empty($row['hamtningsdatum_inkl_tid']) ? Carbon::createFromFormat('d/m/Y H:i', $row['hamtningsdatum_inkl_tid']) : now(),
        ]);
    }

    /**
     * Gets the row count.
     *
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rows;
    }
}
