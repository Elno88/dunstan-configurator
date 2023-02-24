<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class BFFForsakring extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-ff-forsakring';
    public $progressbar = 55;

    public function view(Request $request)
    {

        // Fetch horse name and usage
        $horse_name = $this->get_data('hastforsakring-b-6.namn');
        $horse_usage = $this->get_data('hastforsakring-b-3.horse_usage');

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

        $next_step = $this->skip_to_next_available_step($this->name);

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
