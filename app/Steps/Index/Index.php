<?php

namespace App\Steps\Index;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Validator;

class Index extends StepAbstract
{
    public $name = 'index';
    public $progressbar = 0;

    public function view(Request $request)
    {
        return view('steps.index');
    }

    public function validateStep(Request $request)
    {
        $input = [
            'forsakring' => $request->get('forsakring')
        ];

        $rules = [
            'forsakring' => 'required|in:hastforsakring,gardsforsakring,trailerforsakring'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        $next_step = $input['forsakring'];

        $this->store_data($input);

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }
}
