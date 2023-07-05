<?php

namespace App\Steps\Trailerinsurance\A;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class A extends StepAbstract
{
    public $name = 'trailerforsakring';
    public $progressbar = 20;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.a.a', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-a1',
        ]);
    }
}
