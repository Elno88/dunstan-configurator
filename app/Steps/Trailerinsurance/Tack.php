<?php

namespace App\Steps\Trailerinsurance;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class Tack extends StepAbstract
{
    public $name = 'trailerforsakring-tack';
    public $progressbar = 100;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function view(Request $request)
    {
        return view('steps.trailerinsurance.tack', [
            //
        ]);
    }

    /**
     * Validates the step.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => '',
        ]);
    }
}
