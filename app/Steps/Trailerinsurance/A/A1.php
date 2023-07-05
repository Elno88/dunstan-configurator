<?php

namespace App\Steps\Trailerinsurance\A;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class A1 extends StepAbstract
{
    public $name = 'trailerforsakring-a1';
    public $progressbar = 40;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.a.a1', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-resultat',
        ]);
    }
}
