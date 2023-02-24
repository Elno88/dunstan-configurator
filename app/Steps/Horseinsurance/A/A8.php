<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A8 extends StepAbstract
{
    public $name = 'hastforsakring-a-8';
    public $progressbar = 85;

    public function view(Request $request)
    {

        // Fetch session data
        $selected_born = $this->get_data($this->name.'.born');

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-a-4.namn');

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

        $next_step = 'hastforsakring-a-9';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
