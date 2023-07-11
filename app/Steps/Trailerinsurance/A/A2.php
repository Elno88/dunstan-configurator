<?php

namespace App\Steps\Trailerinsurance\A;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class A2 extends StepAbstract
{
    public $name = 'trailerforsakring-a2';
    public $progressbar = 48;
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
        return view('steps.trailerinsurance.a.a2', [
            //
        ]);
    }

    public function validateStep(Request $request)
    {
        $ssn = preg_replace('~\D~', '', $request->get('ssn'));

        $validator = Validator::make(
            [
                'ssn' => $ssn,
            ],
            [
                'ssn' => [
                    'required',
                    'regex:/(^[\d]{12}$)/u',
                    'bail'
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => $validator->errors()->toArray()
            ]);
        }

        try {
            $customer = (new FocusApi)->get_customer($ssn);
        } catch (FocusApiException $e) {
            try {
                $new_customer = (new FocusApi())->get_address($ssn);
                $customer['kund'] = $new_customer;
                $customer['kund']['namn'] = $new_customer['fornamn'] ?? '';
                $customer['kund']['typ'] = 'person';

            } catch (FocusApiException $e) {
                $customer = null;
            }
        }

        $this->store_data($ssn, 'ssn');
        $this->store_data($customer, 'customer');

        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-resultat',
        ]);
    }
}
