<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B10 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-10';
    public $progressbar = 80;

    public function view(Request $request)
    {

        // Fetch session data
        $selected_born = $this->get_data($this->name.'.born');

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-b-6.namn');

        return view('steps.horseinsurance.a.a8', [
            'selected_born' => $selected_born,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'born' => $request->get('born')
        ];

        $rules = [
            'born'        => 'required|in:Ja,Nej'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        if($input['born'] == 'Nej'){
            $input['risk'] = 1;
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
