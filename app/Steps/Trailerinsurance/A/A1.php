<?php

namespace App\Steps\Trailerinsurance\A;

use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class A1 extends StepAbstract
{
    public $name = 'trailerforsakring-a1';
    public $progressbar = 32;
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
        $vehicle = $this->get_data('vehicle');

        return view('steps.trailerinsurance.a.a1', [
            'vehicle' => $vehicle,
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
            'next_step' => 'trailerforsakring-a2',
        ]);
    }
}
