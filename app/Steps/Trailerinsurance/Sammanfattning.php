<?php

namespace App\Steps\Trailerinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Libraries\Woocommerce\WoocommerceApi;
use App\Libraries\Woocommerce\WoocommerceApiException;
use App\Mail\Trailer as TrailerMail;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Illuminate\View\View
     */
    public function view(Request $request)
    {
        $options = $this->get_data('options');
        $customer = $this->get_data('customer');
        $vehicle = $this->get_data('vehicle');
        $ssn = $this->get_data('ssn');

        $betalningstermin = session()->get('data.trailerforsakring-sammanfattning.betalningstermin', 1);

        return view('steps.trailerinsurance.sammanfattning', [
            'options' => $options,
            'safety' => $options['safety'] ?? 'Normal',
            'form' => $options['form'] ?? 'Grund',
            'benefit' => $options['benefit'] ?? null,
            'date' => $options['date'] ?? null,
            'vehicle' => $vehicle ?? null,
            'customer' => $customer ?? null,
            'ssn' => $ssn ?? null,
            'uppsagning' => $options['uppsagning'] ?? 0,
            'betalningstermin' => $betalningstermin
        ]);
    }

    /**
     * Validates the step.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStep(Request $request)
    {
        $input = [
            'startdatum' => request()->get('startdatum'),
            'email' => request()->get('email'),
            'telefon' => request()->get('telefon'),
            'betalningsmetod' => request()->get('betalningsmetod'),
            'betalningstermin' => request()->get('betalningstermin'),
            'autogiro_clearing' => request()->get('autogiro_clearing'),
            'autogiro_account' => request()->get('autogiro_account'),
            'term' => request()->get('term'),
        ];

        $rules = [
            'email' => 'required|email',
            'telefon' => 'required',
            'betalningsmetod' => 'required|in:autogiro,faktura',
            'betalningstermin' => 'required|in:1,3,12',
            'autogiro_clearing' => 'required_if:betalningsmetod,autogiro',
            'autogiro_account' => 'required_if:betalningsmetod,autogiro',
            'term' => 'required|accepted',
            'startdatum' => 'required|date:Y-m-d|after_or_equal:'.today()->format('Y-m-d').'|before:'.today()->addDays(90)->format('Y-m-d'),
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

            $focusApi = new FocusApi;

            if (isset($input['betalningsmetod']) && $input['betalningsmetod'] == 'autogiro') {
                try {
                    $focus_autogiro_response = $focusApi->valid_autogiro_account($input['autogiro_clearing'], $input['autogiro_account']);
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

        $options = $this->get_data('options');
        $input['betalningstermin'] = session()->get('data.trailerforsakring-sammanfattning.betalningstermin', 1);
        $input['uppsagning'] = $options['uppsagning'] ?? 0;
        $this->store_data($input, 'trailerforsakring-sammanfattning');

        return response()->json([
            'status' => 1,
            'next_step' => 'trailerforsakring-tack',
        ]);
    }

    public function bankid_sign()
    {
        // Check validation during signing as well
        $validation = $this->validateStep(request())->getData('true');
        if (!isset($validation['status']) || $validation['status'] !== 1) {
            return $validation;
        }

        $focusApi = new FocusApi();
        $focus_data = $focusApi->get_shared_focus_data();

        // Get civic number from session
        $civic_number = $focus_data['civic_number'];

        // Sign
        try {
            // start bankid transaction
            $bankid_sign_text = 'Jag godkänner härmed köp av försäkring hos Dunstan AB';
            $data = $focusApi->bankid_sign($civic_number, $bankid_sign_text, '', 1);

            $response = [
                'status' => 1,
                'orderRef' => $data['orderRef']
            ];
        } catch (FocusApiException $e) {
            report($e);
            $error_text = 'Ett okänt fel har inträffat';
            if ($e->getCode() == 400) {
                $error_json = json_decode($e->getMessage(), true);
                $error_text = $error_json['message'] ?? $error_text;
            }

            $response = [
                'status' => 0,
                'message' => $error_text
            ];

            return $response;
        }

        return $response;
    }

    public function bankid_status()
    {
        $orderRef = request()->get('orderRef');
        $conf_bankid_force_retries = config('services.focus.bankid_status_force_retries');
        $force_retries = request()->get('force_retries', $conf_bankid_force_retries);
        $focusApi = new FocusApi();

        if ($force_retries != $conf_bankid_force_retries) {
            $attempt = $conf_bankid_force_retries - $force_retries;

            $this->log_data('Retrying to get BankID status, attempt '.$attempt.'.', [
                'orderRef' => $orderRef
            ], 'warning');
        }

        if (session()->has('bankid.'.$orderRef) && session()->get('bankid.'.$orderRef) == 'COMPLETE') {
            $response = [
                'status' => 1,
                'next_step' => 'tack'
            ];

            session()->forget('bankid');

            return $response;
        }

        // Check sign status
        try {
            // start bankid status check
            $data = $focusApi->bankid_login_check($orderRef);
            $bankid_status = $data['status'];
        } catch (FocusApiException $e) {
            report($e);
            $error_text = 'Ett okänt fel har inträffat';
            if ($e->getCode() == 400) {
                $error_json = json_decode($e->getMessage(), true);
                $error_text = $error_json['message'] ?? $error_text;
            }

            $response = [
                'status' => 0,
                'message' => $error_text
            ];

            return $response;
        }

        $pending_statuses = [
            'OUTSTANDING_TRANSACTION',
            'STARTED',
            'USER_SIGN'
        ];

        if (in_array($bankid_status, $pending_statuses)) {
            $response = [
                'status' => 2,
                'message' => 'Polling bankid status..'
            ];

            return $response;
        }

        // Send to focus
        if ($bankid_status == 'COMPLETE') {
            $focus_data = $focusApi->get_shared_focus_data();

            try {
                // Log data after bankid have been signed, this is done before anything is sent to focus.
                $this->log_data('Bankid signed. Shared data.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data' => $focus_data
                ]);

                $this->send_focus_data();

                session()->put('bankid', [
                    $orderRef => $bankid_status
                ]);
                $response = [
                    'status' => 1,
                    'next_step' => 'tack'
                ];
            } catch (FocusApiException $e) {
                report($e);

                // Logging
                $this->log_data('Sending data to focus failed.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data' => $focus_data
                ], 'error');

                $response = [
                    'status' => 0,
                    'message' => 'Ett fel har inträffat.'
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'message' => 'Ett fel har inträffat vid bankid status check.',
                'focus_response' => $data,
            ];

            // Logging
            $this->log_data('BankID status check failed.', [
                'orderRef' => $orderRef,
                'data' => $response
            ], 'error');
        }

        return $response;
    }

    public function send_focus_data()
    {
        $focusApi = new FocusApi();

        // Hämta samlad data
        $data = $focusApi->get_shared_focus_data();

        // Update or create customer with details
        $customer_data = [];
        if (isset($data['email']) && !empty($data['email'])) {
            $customer_data['email'] = $data['email'];
        }
        if (isset($data['telefon']) && !empty($data['telefon'])) {
            $customer_data['mobil'] = $data['telefon'];
            $customer_data['telefon'] = $data['telefon'];
        }

        // First get customer
        $create_customer = false;
        try {
            // get customer
            $focus_customer_response = $focusApi->get_customer($data['civic_number']);
        } catch (FocusApiException $e) {
            // probably means customer doesnt exist, create it?
            $create_customer = true;
        }

        // Logging
        $this->log_data('Customer data. ', [
            'session_id' => $data['session_id'] ?? null,
            'data' => $customer_data
        ]);

        if ($create_customer) {
            // create customer
            $focus_customer = $focusApi->create_customer($data['civic_number'], $customer_data);
        } else {
            // update customer
            $focus_customer = $focusApi->update_customer($data['civic_number'], $customer_data);
        }

        // Get customer id
        $customer_id = $focus_customer['id'];

        // Om betalning är autogiro, registrera autogiro här
        if (isset($data['betalningsmetod']) && $data['betalningsmetod'] == 'autogiro') {
            $payment_clearing_number = $data['autogiro_clearing'];
            $payment_account_number = $data['autogiro_account'];
            try {
                $focusApi->register_autogiro($customer_id, $payment_clearing_number, $payment_account_number);

                // Logging
                $this->log_data('Stored autogiro to focus.', [
                    'session_id' => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data' => [
                        'clearing_number' => $payment_clearing_number ?? null,
                        'account_number' => $payment_account_number ?? null
                    ]
                ]);
            } catch (FocusApiException $e) {
                report($e);

                // Logging
                $this->log_data('Could not store autogiro to focus.', [
                    'session_id' => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data' => [
                        'clearing_number' => $payment_clearing_number ?? null,
                        'account_number' => $payment_account_number ?? null
                    ]
                ], 'error');
            }
        }


        $moments[] = config('services.focus.live') ? 45 : 47; //$data['trailerforsakring'];

        $moments_ids = implode(',', $moments);
        $focus_moments_fields = $focusApi->build_focus_fields($moments, $data);

        $regnr = '';
        if (config('services.focus.live')) {
            $regnr = $focus_moments_fields['630'];
        } else {
            $regnr = $focus_moments_fields['652'];
        }

        $notes = 'Kunden har tecknat försäkring via webben för '.$regnr.'.';

        // Logging
        $this->log_data('Adding insurance, sent to focus.', [
            'session_id' => $data['session_id'] ?? null,
            'customer_id' => $customer_id ?? null,
            'moment_ids' => $moments_ids ?? null,
            'fields' => $focus_moments_fields ?? null,
            'question_response_id' => null,
            'payment_terms' => $data['betalningstermin'] ?? 1,
            'start_date' => $data['startdatum'] ?? null,
            'invoice_status' => $invoice_status ?? null,
            'notes' => $notes ?? null
        ]);

        $focus_moments_response = $focusApi->add_insurance(
            $customer_id,
            $moments_ids,
            $focus_moments_fields,
            null,
            8,
            $data['betalningstermin'],
            null,
            $data['startdatum'],
            null,
            $notes
        );

        $session_id = $data['session_id'] ?? null;

        // Add notering to customer
        $this->send_note($focusApi, $session_id, $customer_id, $notes);

        // Lägg till notering om uppsägning
        if (isset($data['uppsagning']) && $data['uppsagning'] === '1') {
            $this->send_note($focusApi, $session_id, $customer_id, "Kunden har begärt hjälp med uppsägning av sin nuvarande försäkring");
        }

        // Add points
        $total_utpris = 0;
        $points = 0;
        $products = [];

        try {
            if (isset($focus_moments_response['utpris'])) {
                $total_utpris += $focus_moments_response['utpris'];
                $products[$focus_moments_response['momentId']] = ['total' => $focus_moments_response['utpris']];
            } else {
                foreach ($focus_moments_response as $moment) {
                    if (isset($moment['utpris'])) {
                        $total_utpris += $moment['utpris'];
                        $products[$moment['momentId']] = ['total' => $moment['utpris']];
                    }
                }
            }
        } catch (FocusApiException $e) {
            report($e);

            // Logging
            $this->log_data('Could not handle response from focus when calculating price.', [
                'session_id' => $data['session_id'] ?? null,
                'customer_id' => $customer_id ?? null
            ], 'error');
        }

        // Create woocommerce users
        if (isset($data['email']) && !empty($data['email'])) {

            if (config('services.woocommerce.create_user')) {
                $woocommerceapi = new WoocommerceApi();

                $nickname = $data['civic_number'].'-'.$customer_id;

                try {
                    $woo_user_response = $woocommerceapi->create_user(
                        $data['email'],
                        null,
                        $nickname,
                    );

                    // Logging
                    $this->log_data('Created user at wordpress instance.', [
                        'session_id' => $data['session_id'] ?? null,
                        'customer_id' => $customer_id ?? null,
                        'email' => $data['email'] ?? null,
                        'nickname' => $nickname ?? null
                    ]);
                } catch (WoocommerceApiException $e) {
                    // Silent error?
                    report($e);

                    // Logging
                    $this->log_data('Could not create user at wordpress instance.', [
                        'session_id' => $data['session_id'] ?? null,
                        'customer_id' => $customer_id ?? null,
                        'email' => $data['email'] ?? null,
                        'nickname' => $nickname ?? null
                    ], 'error');
                }
            }

            // Mailchimp
            try {
                $mailchimp_tags = ['LEADfranKonfig', 'LeadWebHastNY'];
                if (isset($data['step_insurance']) && $data['step_insurance'] == 'hastforsakring-b-1') {
                    $mailchimp_tags = ['LEADfranKonfig', 'LeadWebHastJAMFOR'];
                }

                // Meta data
                $meta = [];
                if (isset($focus_customer['fornamn']) && !empty($focus_customer['fornamn'])) {
                    $meta['FNAME'] = $focus_customer['fornamn'];
                }
                if (isset($focus_customer['efternamn']) && !empty($focus_customer['efternamn'])) {
                    $meta['LNAME'] = $focus_customer['efternamn'];
                }
                // hästens namn
                if (isset($data['namn']) && !empty($data['namn'])) {
                    $meta['MMERGE9'] = $data['namn'];
                }
                // Hästens födelsedata
                if (isset($data['fodelsedatum']) && !empty($data['fodelsedatum'])) {
                    try {
                        $meta['MMERGE11'] = \Carbon\Carbon::parse($data['fodelsedatum'])->format('d/m/Y');
                    } catch (\Exception $e) {
                        // silent error
                        unset($meta['MMERGE11']);
                    }
                }

                $this->log_data('Mailchimp metadata.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data' => [
                        'meta' => $meta,
                        'tags' => $mailchimp_tags
                    ]
                ]);

                $mailchimpapi = new MailchimpApi;
                $mailchimpapi->subscribe_member($data['email'], $meta);
                $mailchimpapi->member_assign_tags($data['email'], $mailchimp_tags);
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Send mail
        try {

            $send_email_to = null;
            if (config('services.focus.live')) {
                $send_email_to = config('services.dunstan.email_live');
            } else {
                $send_email_to = config('services.dunstan.email_test');
            }

            // Dunstan
            Mail::to($send_email_to)->send(new TrailerMail(
                'Nytecknad trailerförsäkring via web',
                [
                    'kundnr' => $focus_customer['kundnr'] ?? '',
                    'fornamn' => $focus_customer['fornamn'] ?? '',
                    'efternamn' => $focus_customer['efternamn'] ?? '',
                ]
            ));
        } catch (\Exception $e) {
            // Silent error if mail fails
            report($e);
        }

        // Store data to tack sida for google ecommerce
        $this->store_data([
            'products' => $products
        ], 'tack');
    }

    private function send_note($focusApi, $session_id, $customer_id, $note)
    {
        try {
            $focus_notering_response = $focusApi->add_note_to_customer($customer_id, $note);

            // Logging
            $this->log_data('Notes', [
                'session_id' => $session_id,
                'customer_id' => $customer_id ?? null,
                'data' => $notes ?? null
            ]);
        } catch (FocusApiException $e) {
            report($e);

            // Logging
            $this->log_data('Could not add note to focus.', [
                'session_id' => $data['session_id'] ?? null,
                'customer_id' => $customer_id ?? null,
                'data' => $notes ?? null
            ], 'error');
        }

        // echo '<pre>'.print_r($focus_moments_response, true).'</pre>';
        // die();
    }

    private function log_data($message, $data = [], $type = 'info')
    {
        Log::channel('dunstan')->{$type}($message, ['data' => $data]);
    }

}
