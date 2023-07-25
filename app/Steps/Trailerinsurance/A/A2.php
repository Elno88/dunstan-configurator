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

        // First get customer
        $create_customer = false;
        $focusApi = new FocusApi();
        try {
            $customer = $focusApi->get_customer($ssn);
        } catch (FocusApiException $e) {
            // probably means customer doesnt exist, create it?
            $create_customer = true;
        }

        try {
            $new_customer = $focusApi->get_address($ssn);
            $customer['kund'] = $new_customer;
            $customer['kund']['typ'] = 'person';
            $customer['kund']['namn'] = $new_customer['fornamn'] . ' ' . $new_customer['efternamn'];
        } catch (FocusApiException $e) {
            $customer = null;
        }

        if ($create_customer)
        {
            // create customer
            $focus_customer = $focusApi->create_customer($ssn, $customer);
        }
        else
        {
            // update customer
            $focus_customer = $focusApi->update_customer($ssn, $customer);
        }

        $this->store_data($ssn, 'ssn');
        $this->store_data($customer, 'customer');

        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-resultat',
        ]);
    }
}
