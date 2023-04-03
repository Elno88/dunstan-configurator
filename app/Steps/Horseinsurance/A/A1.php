<?php

namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A1 extends StepAbstract
{
    public $name = 'hastforsakring-a-1';
    public $progressbar = 15;
    public $horse_usage;

    public function __construct()
    {
        $this->horse_usage = [
            1 => 'Ridhäst',
            7 => 'Ponny',
            5 => 'Trav & Galopp',
            3 => 'Avel',
            6 => 'Föl & Unghäst',
            2 => 'Foster',
            8 => 'Islandshäst'
        ];
    }

    public function view(Request $request)
    {

        // Fetch session data
        $selected_horse_usage = $this->get_data($this->name . '.horse_usage');

        return view('steps.horseinsurance.a.a1', [
            'horse_usage' => $this->horse_usage,
            'selected_horse_usage' => $selected_horse_usage
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'horse_usage' => $request->get('horse_usage')
        ];

        $rules = [
            'horse_usage'        => 'required'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        if (isset($this->horse_usage[$input['horse_usage']])) {
            $input['horse_usage_label'] = $this->horse_usage[$input['horse_usage']];
        }

        // Store data
        $this->store_data($input);

        $next_step = 'hastforsakring-a-2';

        // Change step if föl
        if ($input['horse_usage'] == 2) {

            // Store data for steps we skip
            $steps_data = [
                // Kön
                'hastforsakring-a-3' => [
                    'gender' => null,
                ],
                // Född
                'hastforsakring-a-8' => [
                    'born' => '',
                ],
            ];

            foreach ($steps_data as $step => $step_data) {
                $this->store_data($step_data, $step);
            }

            $next_step = 'hastforsakring-a-2';
        }

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }
}
