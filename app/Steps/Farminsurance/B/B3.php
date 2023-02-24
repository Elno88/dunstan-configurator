<?php namespace App\Steps\Farminsurance\B;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;
use PDF;

class B3 extends StepAbstract
{
    public $name = 'gardsforsakring-b-3';
    public $progressbar = 90;

    public function view(Request $request)
    {

        // Fetch session data
        $insurances = $this->get_data('gardsforsakring-b-2.insurances') ?? [];
        $insurance_company_name = $this->get_data('gardsforsakring-b-2.insurance_company_name') ?? 'Försäkringsbolaget';

        return view('steps.farminsurance.b.b3', [
            'insurances' => $insurances,
            'insurance_company_name' => $insurance_company_name
        ]);

    }

    public function validateStep(Request $request)
    {

        $insurances = $this->get_data('gardsforsakring-b-2.insurances') ?? [];

        $input = [];

        $rules = [];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }


        // if empty, set step to manual
        if(empty($insurances)){

            $steps_data['gardsforsakring'] = [
                'gardsforsakring' => 'gardsforsakring-a-1'
            ];

            $gardsforsakring_b_1 = $this->get_data('gardsforsakring-b-1') ?? [];
            $steps_data['gardsforsakring-a-1'] = $gardsforsakring_b_1;

            foreach($steps_data as $step => $step_data){
                $this->store_data($step_data, $step);
            }

        }

        $this->store_data($input);

        $next_step = 'gardsforsakring-kontakt';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }



}
