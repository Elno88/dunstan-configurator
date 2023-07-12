<?php

namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A4 extends StepAbstract
{

    public $name = 'hastforsakring-a-4';
    public $progressbar = 45;

    public function view(Request $request)
    {
        // Fetch session data
        $name = $this->get_data($this->name . '.namn');
        $farg = $this->get_data($this->name . '.farg');

        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');

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

        $next_step = 'hastforsakring-a-5';

        // Fetch session data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');
        if ($horse_usage == 2) {
            $next_step = 'hastforsakring-a-ff-forsakring';
        } elseif ($horse_usage == 8) {
            $this->store_data([
                'breed' => 'Islandshäst',
            ], 'hastforsakring-a-5');

            $next_step = 'hastforsakring-a-7';
        }

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }
}
