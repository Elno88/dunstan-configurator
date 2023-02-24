<?php namespace App\Steps\Farminsurance\B;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Validator;
use PDF;

class B2 extends StepAbstract
{
    public $name = 'gardsforsakring-b-2';
    public $progressbar = 90;

    public function view(Request $request)
    {

        // Fetch session data
        if(config('services.insurley.live')){
            $client_id = config('services.insurley.client_id_gard_live');
        } else {
            $client_id = config('services.insurley.client_id_test');
        }
        $insurley_iframe_url = config('services.insurley.url').'?clientId='.$client_id;

        return view('steps.farminsurance.b.b2', [
           'insurley_iframe_url' => $insurley_iframe_url
        ]);

    }

    public function validateStep(Request $request)
    {

        $input = [
            'insurely' => $request->get('insurely'),
            'insurances' => [],
            'civic_number' => '',
            'insurance_company_name' => '',
            'pdf_data' => '',
        ];

        $rules = [
            'insurely'        => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        // Pick only the insurances we are interested in from insurley
        $insurances = [];
        if(!empty($input['insurely'])){

            // Only pick insurances that are of interest
            $insurley_insurances = json_decode($input['insurely'], true);

            if(!empty($insurley_insurances)){
                foreach($insurley_insurances as $insurance){

                    if(
                        isset($insurance['insurance']['insuranceSubType']) &&
                        in_array($insurance['insurance']['insuranceSubType'], [
                            'condoInsurance',
                            'accidentInsurance',
                            'farmInsurance',
                            'villaInsurance'
                        ])
                    ){

                        // If we already have this insurance, skip
                        if(isset($insurances[$insurance['insurance']['insuranceSubType']])){
                            continue;
                        }

                        $insurances[$insurance['insurance']['insuranceSubType']] = $insurance;

                        if(empty($input['civic_number']) && isset($insurance['personalInformation']['PERSONAL_NUMBER'])){
                            $input['civic_number'] = $insurance['personalInformation']['PERSONAL_NUMBER'];
                        }

                        if(empty($input['insurance_company_name']) && isset($insurance['insurance']['insuranceCompany'])){
                            $input['insurance_company_name'] = $insurance['insurance']['insuranceCompany'];
                        }
                    }
                }
            }
        }
        $input['insurances'] = $insurances;

        // if not empty, store pdf data, reset firtsstep just in case
        if(!empty($input['insurances'])){

            // Just in case
            $steps_data['gardsforsakring'] = [
                'gardsforsakring' => 'gardsforsakring-b-1'
            ];

            foreach($steps_data as $step => $step_data){
                $this->store_data($step_data, $step);
            }

            $input['pdf_data'] = $this->generate_pdf($input);

        }

        // Store data
        $this->store_data($input);

        $next_step = 'gardsforsakring-b-3';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }

    public function generate_pdf($data)
    {
        // Generate pdf file from insurley insurance data
        $pdf_file = PDF::loadView('pdf.gardsforsakring', $data)->output();
        return base64_encode($pdf_file);
    }

}
