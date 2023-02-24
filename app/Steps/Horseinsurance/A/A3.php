<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A3 extends StepAbstract
{
    public $name = 'hastforsakring-a-3';
    public $progressbar = 35;

    public function view(Request $request)
    {

        // Get data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');

        // Get genders
        $focusapi = new FocusApi();
        $focus_response = $focusapi->get_moment(22);

        $genders = [];
        foreach($focus_response as $moments){
            foreach($moments['falt'] as $field){
                if($field['namn'] == 'Kön'){
                    $genders = $field['alternativ'];
                    break 2;
                }
            }
        }

        // Om avel är vald, ta bort valack
        if(isset($horse_usage) && !empty($horse_usage) && $horse_usage == 3){
            foreach($genders as $key => $gender){
                if($gender == 'Valack'){
                    unset($genders[$key]);
                }
            }
        }

        // Fetch session data
        $selected_gender = $this->get_data($this->name.'.gender');

        return view('steps.horseinsurance.a.a3', [
            'genders' => $genders,
            'selected_gender' => $selected_gender
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'gender' => $request->get('gender')
        ];

        $rules = [
            'gender'        => 'required'
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

        return response()->json([
            'status' => 1,
            'next_step' => 'hastforsakring-a-4'
        ]);

    }
}
