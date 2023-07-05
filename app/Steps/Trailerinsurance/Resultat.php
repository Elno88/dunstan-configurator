<?php

namespace App\Steps\Trailerinsurance;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class Resultat extends StepAbstract
{
    public $name = 'trailerforsakring-resultat';
    public $progressbar = 60;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.resultat', [
            'deductibles' => [],
            'types' => [],
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-sammanfattning',
        ]);
    }
}
