<?php

namespace App\Steps\Trailerinsurance\A;

use App\Services\Biluppgifter\Biluppgifter;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class A extends StepAbstract
{
    public $name = 'trailerforsakring';
    public $progressbar = 16;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function view(Request $request)
    {
        return view('steps.trailerinsurance.a.a', [
            //
        ]);
    }

    /**
     * Validates the step.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStep(Request $request)
    {
        $validator = Validator::make(
            [
                'regnr' => Str::upper($request->get('regnr')),
            ],
            [
                'regnr' => [
                    'required',
                    'regex:/(^[A-Za-z]{3}[\d]{2}[\w]{1}$)/u',
                    'bail'
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => $validator->errors()->toArray()
            ]);
        }

        $vehicle = (new Biluppgifter)->findByRegNo($request->get('regnr'));

        if (empty($vehicle)) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => 'Fordonet kunde inte hittas',
            ]);
        }

        $data = [
            'regnr'          => $vehicle['attributes']['regno'] ?? null,
            'make'           => $vehicle['basic']['data']['make'] ?? null,
            'model'          => $vehicle['basic']['data']['model'] ?? null,
            'year'           => $vehicle['basic']['data']['vehicle_year'] ?? null,
            'total_weight'   => $vehicle['technical']['data']['total_weight'] ?? null,
            'service_weight' => $vehicle['technical']['data']['kerb_weight'] ?? null,
            'chassi'         => $vehicle['technical']['data']['chassi'] ?? null,
        ];

        $this->store_data($data, 'vehicle');

        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-a1',
        ]);
    }
}
