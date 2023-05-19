<?php

namespace App\Services\Lead;

use App\Models\Lead;
use Carbon\Carbon;

class InsurleyLead
{
    public function process(array $insurances = [])
    {
        foreach ($insurances as $insurance) {
            $this->saveToDatabase($insurance);
        }
    }

    protected function saveToDatabase($insurance)
    {
        Lead::create([
            'external_id'          => $insurance['externalId'],
            'ssn'                  => $insurance['civic_number'],
            'name'                 => $insurance['insuranceHolderName'] ?? null,
            'animal_name'          => $insurance['animalName'] ?? null,
            'animal_breed'         => $insurance['animalBreed'] ?? null,
            'animal_gender'        => $insurance['animalGender'] ?? null,
            'animal_chip_number'   => $insurance['chipNumber'] ?? null,
            'animal_birth'         => $insurance['dateOfBirth'] ?? null,
            'animal_price'         => $insurance['animalPurchasePrice'] ?? null,
            'insurance_company'    => $insurance['insuranceCompany'] ?? null,
            'insurance_name'       => $insurance['insuranceName'] ?? null,
            'insurance_type'       => $insurance['insuranceType'] ?? null,
            'insurance_sub_type'   => $insurance['insuranceSubType'] ?? null,
            'insurance_number'     => $insurance['insuranceNumber'] ?? null,
            'premium_frequency'    => $insurance['premiumFrequency'] ?? null,
            'premium_amount'       => $insurance['premiumAmountYearRounded'] ?? null,
            'premium_method'       => $insurance['paymentMethod'] ?? null,
            'veterinary_amount'    => $insurance['veterinaryCareAmount'] ?? null,
            'veterinary_remaining' => $insurance['veterinaryCareAmountRemaining'] ?? null,
            'coming'               => $insurance['coming'] === 'true' ? true : false,
            'employer_paid'        => $insurance['employerPaid'] === 'true' ? true : false,
            'other'                => $insurance['otherInsuranceHolder'] ?? null,
            'data'                 => $insurance,
            'started_at'           => Carbon::parse($insurance['startDate']),
            'renewal_at'           => Carbon::parse($insurance['renewalDate']),
        ]);
    }
}
