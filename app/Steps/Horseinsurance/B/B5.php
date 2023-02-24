<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B5 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-5';
    public $progressbar = 45;

    public function view(Request $request)
    {

        // Get data
        $horse_usage = $this->get_data('hastforsakring-b-3.horse_usage');

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

        $next_step = $this->skip_to_next_available_step($this->name);

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
