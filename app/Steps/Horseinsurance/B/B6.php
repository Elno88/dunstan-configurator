<?php

namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B6 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-6';
    public $progressbar = 52;

    public function view(Request $request)
    {

        // Fetch session data
        $name = $this->get_data($this->name . '.namn');
        $farg = $this->get_data($this->name . '.farg');

        $horse_usage = $this->get_data('hastforsakring-b-3.horse_usage');

        $colors = [
            'Brun',
            'Fux',
            'Skimmel',
            'Svart',
            'Black',
            'Skäck',
            'Isabell',
            'Tigrerad',
        ];

        return view('steps.horseinsurance.a.a4', [
            'farg'        => $farg,
            'colors'      => $colors,
            'name'        => $name,
            'horse_usage' => $horse_usage
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'namn' => $request->get('namn'),
            'farg' => $request->get('farg'),
        ];

        $rules = [
            'namn' => 'required',
            'farg' => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
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
