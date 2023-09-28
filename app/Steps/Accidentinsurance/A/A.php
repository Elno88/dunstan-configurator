<?php

namespace App\Steps\Accidentinsurance\A;

use App\Actions\ValidatePersonAction;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepAbstract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class A extends StepAbstract
{
    public $name = 'olycksfallsforsakring';
    public $progressbar = 25;
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
        return view('steps.accidentinsurance.a.a', [
            'ssn'   => $this->get_data('ssn'),
            'email' => $this->get_data('email'),
            'phone' => $this->get_data('phone'),
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
        $ssn = preg_replace('~\D~', '', $request->get('ssn'));

        $validator = Validator::make(
            [
                'ssn'   => $ssn,
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
            ],
            [
                'ssn' => ['required', 'numeric', 'regex:/(^(19|20)[\d]{10}$)/u', 'bail'],
                'email' => 'required|email|bail',
                'phone' => 'nullable|string|bail',
            ],
            [
                'ssn.required'   => 'Du måste ange ett personnummer',
                'ssn.regex'      => 'Du måste ange ett giltigt personnummer',
                'email'          => 'Du måste ange en giltigt e-postadress',
                'email.required' => 'Du måste ange en e-postadress',
                'email.regex'    => 'Du måste ange en giltigt e-postadress',
            ]
        );

        $validator->after(function ($validator) use ($ssn) {
            try {
                if (!(new ValidatePersonAction)->execute($ssn)) {
                    $validator->errors()->add('ssn', 'Du måste ange ett giltigt personnummer');
                }
            } catch (\Exception $e) {
                $validator->errors()->add('ssn', $e->getMessage());
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => $validator->errors()->toArray()
            ]);
        }

        $this->store_data($ssn, 'ssn');
        $this->store_data($ssn, 'civic_number');
        $this->store_data($request->get('email'), 'email');
        $this->store_data($request->get('phone'), 'phone');

        try {
            $customer = (new FocusApi)->get_customer($ssn);
        } catch (FocusApiException $exception) {
            if (Str::contains($exception->getMessage(), 'Felaktigt personnummer')) {
                return response()->json([
                    'status'  => 0,
                    'display' => 1,
                    'errors'  => [
                        'ssn' => [
                            'Du måste ange ett giltigt personnummer',
                        ],
                    ],
                ]);
            }

            $data = (new FocusApi)->get_address($ssn);

            $customer = [];
            $customer['kund'] = $data;
            $customer['kund']['typ'] = 'person';
            $customer['kund']['namn'] = $data['fornamn'] . ' ' . $data['efternamn'];
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => $exception->getMessage(),
            ]);
        }

        if (empty($customer)) {
            return response()->json([
                'status'  => 0,
                'display' => 1,
                'errors'  => 'Det gick inte att hämta kunduppgifter just nu.'
            ]);
        }

        if (empty($customer['kund']['email'])) {
            $customer['kund']['email'] = $request->get('email');
        }

        if (empty($customer['kund']['telefon'])) {
            $customer['kund']['telefon'] = $request->get('phone');
        }

        $this->store_data($customer, 'customer');

        return response()->json([
            'status'    => 1,
            'next_step' => 'olycksfallsforsakring-resultat',
        ]);
    }
}
