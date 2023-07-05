<?php

namespace App\Steps\Trailerinsurance\A;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class A2 extends StepAbstract
{
    public $name = 'trailerforsakring-a2';
    public $progressbar = 50;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.a.a2', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-a3',
        ]);
    }
}
