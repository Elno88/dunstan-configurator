<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A7 extends StepAbstract
{
    public $name = 'hastforsakring-a-7';
    public $progressbar = 75;
    public $skipable = true;

    public function view(Request $request)
    {

        // Fetch session data
        $chip_number = $this->get_data($this->name.'.chip_number');

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-a-4.namn');

        return view('steps.horseinsurance.a.a7', [
            'chip_number' => $chip_number,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'chip_number' => $request->get('chip_number'),
            'skip'  => $request->get('skip', 0)
        ];

        $rules = [
            'chip_number'        => 'required'
        ];

        // Skip
        if($input['skip'] == 1 && $this->skipable){
            $input['chip_number'] = null;
        // Validate
        } else {
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                $response = [
                    'status' => 0,
                    'errors' => $validator->errors()->toArray()
                ];
                return response()->json($response);
            }
        }

        // Store data
        $this->store_data($input);

        $next_step = 'hastforsakring-a-8';

        // Fetch session data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');
        if($horse_usage == 2){
            $next_step = 'hastforsakring-a-9';
        }

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
