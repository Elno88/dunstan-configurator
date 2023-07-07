<?php

namespace App\Steps\Trailerinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class Sammanfattning extends StepAbstract
{
    public $name = 'trailerforsakring-sammanfattning';
    public $progressbar = 80;
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
        $options = $this->get_data('options');
        $vehicle = $this->get_data('vehicle');
        $customer = $this->get_data('customer');
        $vehicle = $this->get_data('vehicle');
        $ssn = $this->get_data('ssn');

        return view('steps.trailerinsurance.sammanfattning', [
            'safety'   => $options['safety'] ?? 'Normal',
            'form'     => $options['form'] ?? 'Grund',
            'benefit'  => $options['benefit'] ?? null,
            'date'     => $options['date'] ?? null,
            'vehicle'  => $vehicle ?? null,
            'customer' => $customer ?? null,
            'ssn'      => $ssn ?? null,
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
        $input = [
            'startdatum'        => request()->get('startdatum'),
            'email'             => request()->get('email'),
            'telefon'           => request()->get('telefon'),
            'betalningsmetod'   => request()->get('betalningsmetod'),
            'betalningstermin'  => request()->get('betalningstermin'),
            'autogiro_clearing' => request()->get('autogiro_clearing'),
            'autogiro_account'  => request()->get('autogiro_account'),
            'term'              => request()->get('term'),
        ];

        $rules = [
            'email'             => 'required|email',
            'telefon'           => 'required',
            'betalningsmetod'   => 'required|in:autogiro,faktura',
            'betalningstermin'  => 'required|in:1,3,12',
            'autogiro_clearing' => 'required_if:betalningsmetod,autogiro',
            'autogiro_account'  => 'required_if:betalningsmetod,autogiro',
            'chip_number'       => 'required',
            'term'              => 'required|accepted',
            'startdatum'        => 'required|date:Y-m-d|after_or_equal:' . today()->format('Y-m-d') . '|before:' . today()->addDays(90)->format('Y-m-d'),
        ];

        $validation_messages = [
            'startdatum.required' => 'Du måste välja ett giltligt startdatum.',
            'startdatum.date' => 'Du måste välja ett giltligt startdatum.',
            'startdatum.after_or_equal' => 'Startdatumet får inte vara tidigare än idag.',
            'startdatum.before' => 'Startdatumet får inte vara längre än 90 dagar fram.'
        ];

        $validator = Validator::make($input, $rules, $validation_messages);

        $validator->after(function ($validator) use (&$input) {
            try {
                $input['telefon'] = PhoneNumber::make($input['telefon'], 'SE')->formatE164();
            } catch (\Exception $e) {
                $validator->errors()->add('telefon', 'Du måste ange korrekt format på telefonnumret');
            }

            $focusapi = new FocusApi;

            if (isset($input['betalningsmetod']) && $input['betalningsmetod'] == 'autogiro') {
                try {
                    $focus_autogiro_response = $focusapi->valid_autogiro_account($input['autogiro_clearing'], $input['autogiro_account']);
                    if (!isset($focus_autogiro_response['data']) || $focus_autogiro_response['data'] != 1) {
                        $validator->errors()->add('autogiro_clearing', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                        $validator->errors()->add('autogiro_account', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                        $validator->errors()->add('autogiro_error', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    }
                } catch (FocusApiException $e) {
                    $validator->errors()->add('autogiro_clearing', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    $validator->errors()->add('autogiro_account', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    $validator->errors()->add('autogiro_error', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-tack',
        ]);
    }
}
