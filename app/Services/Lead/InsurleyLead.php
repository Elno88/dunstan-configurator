<?php

namespace App\Services\Lead;

use App\Models\Lead;
use Carbon\Carbon;

class InsurleyLead
{
    /**
     * Handles the leads.
     *
     * @param array $insurances
     *
     * @return void
     */
    public function handle(array $insurances = [])
    {
        foreach ($insurances as $insurance) {
            $this->saveToDatabase($insurance);
        }
    }

    /**
     * Saves to database.
     *
     * @param array $insurance
     *
     * @return \App\Models\Lead
     */
    protected function saveToDatabase($insurance)
    {
        return Lead::create([
            'external_id'          => $insurance['externalId'],
            'ssn'                  => $insurance['civic_number'],
            'name'                 => $insurance['insuranceHolderName'] ?? null,
            'address'              => $insurance['insuranceHolderStreetAddress'] ?? null,
            'zip'                  => $insurance['insuranceHolderPostalCode'] ?? null,
            'city'                 => $insurance['insuranceHolderPostalCode'] ?? null,
            'animal_name'          => $insurance['animalName'] ?? null,
            'animal_breed'         => $insurance['animalBreed'] ?? null,
            'animal_gender'        => $insurance['animalGender'] ?? null,
            'animal_chip_number'   => $insurance['chipNumber'] ?? null,
            'animal_birth'         => !empty($insurance['dateOfBirth']) ? Carbon::parse($insurance['dateOfBirth'])->format('Y-m-d') : null,
            'animal_price'         => $insurance['animalPurchasePrice'] ?? 0,
            'insurance_company'    => $insurance['insuranceCompany'] ?? null,
            'insurance_name'       => $insurance['insuranceName'] ?? null,
            'insurance_type'       => $insurance['insuranceType'] ?? null,
            'insurance_sub_type'   => $insurance['insuranceSubType'] ?? null,
            'insurance_number'     => $insurance['insuranceNumber'] ?? null,
            'premium_frequency'    => ($insurance['premiumFrequency'] > 0 && $insurance['premiumFrequency'] <= 12) ? $insurance['premiumFrequency'] : 12,
            'premium_amount'       => $insurance['premiumAmountYearRounded'] ?? 0,
            'premium_method'       => $insurance['paymentMethod'] ?? null,
            'veterinary_amount'    => $insurance['veterinaryCareAmount'] ?? 0,
            'veterinary_remaining' => $insurance['veterinaryCareAmountRemaining'] ?? 0,
            'coming'               => $insurance['coming'] === 'true' ? true : false,
            'employer_paid'        => $insurance['employerPaid'] === 'true' ? true : false,
            'other'                => $insurance['otherInsuranceHolder'] ?? null,
            'data'                 => $insurance,
            'started_at'           => !empty($insurance['startDate']) ? Carbon::parse($insurance['startDate']) : null,
            'renewal_at'           => !empty($insurance['renewalDate']) ? Carbon::parse($insurance['startDate']) : null,
        ]);
    }
}
