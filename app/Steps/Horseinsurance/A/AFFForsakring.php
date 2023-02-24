<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class AFFForsakring extends StepAbstract
{

    public $name = 'hastforsakring-a-ff-forsakring';
    public $progressbar = 50;

    public function view(Request $request)
    {

        // Fetch horse name and usage
        $horse_name = $this->get_data('hastforsakring-a-4.namn');
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');

        $focusapi = new FocusApi();
        $focus_response = $focusapi->get_moment(26);

        // Fetch forsakringstypes from focus
        $insurance_type = [];
        foreach($focus_response as $moments){
            foreach($moments['falt'] as $field){
                if($field['namn'] == 'Stoet försäkrad'){
                    $insurance_type = $field['alternativ'];
                    break 2;
                }
            }
        }

        // Fetch session data
        $selected_insurance_type = $this->get_data($this->name.'.insurance_type');

        return view('steps.horseinsurance.a.affforsakring', [
            'horse_name' => $horse_name,
            'horse_usage' => $horse_usage,
            'insurance_type' => $insurance_type,
            'selected_insurance_type' => $selected_insurance_type
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'insurance_type'             => $request->get('insurance_type'),
        ];

        $rules = [
            'insurance_type'             => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        // Store data
        $this->store_data($input);

        $next_step = 'hastforsakring-a-5';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
