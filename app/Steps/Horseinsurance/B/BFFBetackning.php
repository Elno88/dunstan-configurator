<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class BFFBetackning extends StepAbstract
{
    use SkipSteps;

    public $name = 'hastforsakring-b-ff-betackning';
    public $progressbar = 63;

    public function view(Request $request)
    {

        // Fetch horse name and usage
        $horse_name = $this->get_data('hastforsakring-b-6.namn');
        $horse_usage = $this->get_data('hastforsakring-b-3.horse_usage');

        $focusapi = new FocusApi();
        $focus_response = $focusapi->get_moment(26);
        //pre($focus_response);

        // Fetch types from focus
        $stallion_covering_type = [];
        foreach($focus_response as $moments){
            foreach($moments['falt'] as $field){
                if($field['namn'] == 'Typ av betäckning'){
                    $stallion_covering_type = $field['alternativ'];
                    break 2;
                }
            }
        }

        // Fetch session data
        $stallion_name = $this->get_data($this->name.'.stallion_name');
        $seminstation = $this->get_data($this->name.'.seminstation');
        $selected_stallion_covering_type = $this->get_data($this->name.'.stallion_covering_type');

        return view('steps.horseinsurance.a.affbetackning', [
            'horse_name' => $horse_name,
            'horse_usage' => $horse_usage,
            'stallion_covering_type' => $stallion_covering_type,
            'stallion_name' => $stallion_name,
            'seminstation' => $seminstation,
            'selected_stallion_covering_type' => $selected_stallion_covering_type
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'stallion_name'             => $request->get('stallion_name'),
            'seminstation'              => $request->get('seminstation'),
            'stallion_covering_type'    => $request->get('stallion_covering_type'),
        ];

        $rules = [
            'stallion_name'             => 'required',
            'stallion_covering_type'    => 'required',
            'seminstation'              => 'required',
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
