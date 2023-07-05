<?php

namespace App\Steps\Trailerinsurance;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class Sammanfattning extends StepAbstract
{
    public $name = 'trailerforsakring-sammanfattning';
    public $progressbar = 80;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.sammanfattning', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-tack',
        ]);
    }
}
