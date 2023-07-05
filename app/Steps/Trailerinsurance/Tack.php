<?php

namespace App\Steps\Trailerinsurance;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class Tack extends StepAbstract
{
    public $name = 'trailerforsakring-tack';
    public $progressbar = 100;
    public $skipable = false;

    public function view(Request $request)
    {
        return view('steps.trailerinsurance.tack', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => '',
        ]);
    }
}
