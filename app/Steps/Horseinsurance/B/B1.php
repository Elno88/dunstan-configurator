<?php

namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Services\Lead\InsurleyLead;
use App\Steps\StepAbstract;
use App\Steps\StepInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class B1 extends StepAbstract
{
    public $name = 'hastforsakring-b-1';
    public $progressbar = 17;
    public $horse_usage;

    public function view(Request $request)
    {

        // Fetch session data
        if (config('services.insurley.live')) {
            $client_id = config('services.insurley.client_id_live');
        } else {
            $client_id = config('services.insurley.client_id_test');
        }
        $insurley_iframe_url = config('services.insurley.url') . '?clientId=' . $client_id;

        return view('steps.horseinsurance.b.b1', [
            'insurley_iframe_url' => $insurley_iframe_url
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'insurances' => $request->get('insurances'),
            'insurance_company_name' => ''
        ];

        $rules = [
            'insurances'        => 'required|array'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        if (isset($input['insurances'][0]['insuranceCompany'])) {
            $input['insurance_company_name'] = $input['insurances'][0]['insuranceCompany'];
        }

        // Store data
        $this->store_data($input);

        // Store lead
        try {
            (new InsurleyLead)->process($input['insurances'] ?? []);
        } catch (\Exception $e) {
            Log::info('Failed to store this lead:');
            Log::info($input['insurances']);
        }

        $next_step = 'hastforsakring-b-2';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }
}
