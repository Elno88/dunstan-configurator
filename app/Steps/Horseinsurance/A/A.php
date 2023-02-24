<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A extends StepAbstract
{

    public $name = 'hastforsakring';
    public $progressbar = 10;

    public function view(Request $request)
    {
        return view('steps.horseinsurance.a.a');
    }

    public function validateStep(Request $request)
    {

        $input = [
            'hastforsakring' => $request->get('hastforsakring')
        ];

        $rules = [
            'hastforsakring'        => 'required|in:hastforsakring-a-1,hastforsakring-b-1'
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

        $next_step = $input['hastforsakring'];

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }

}
