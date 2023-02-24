<?php namespace App\Steps\Farminsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A extends StepAbstract
{

    public $name = 'gardsforsakring';
    public $progressbar = 10;

    public function view(Request $request)
    {
        return view('steps.farminsurance.a.a');
    }

    public function validateStep(Request $request)
    {

        $input = [
            'gardsforsakring' => $request->get('gardsforsakring')
        ];

        $rules = [
            'gardsforsakring'        => 'required|in:gardsforsakring-a-1,gardsforsakring-b-1'
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

        $next_step = $input['gardsforsakring'];

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }

}
