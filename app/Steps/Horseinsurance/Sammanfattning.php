<?php

namespace App\Steps\Horseinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Libraries\Woocommerce\WoocommerceApi;
use App\Libraries\Woocommerce\WoocommerceApiException;
use App\Mail\Booking as BookingMail;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;


class Sammanfattning extends StepAbstract
{
    public $name = 'sammanfattning';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {

        $focusapi = new FocusApi();
        $focus_data = $focusapi->get_shared_focus_data();
        $data = $this->build_data($focusapi, $focus_data);
        $price = $this->get_price();

        // försäkringar
        $forsakringar = [];
        $forsakring_ver = $this->get_data('resultat.veterinarvardsforsakring');
        $forsakring_liv = $this->get_data('resultat.livforsakring');
        if (isset($forsakring_ver) && !empty($forsakring_ver))
        {
            $forsakringar[] = $forsakring_ver;
        }
        if (isset($forsakring_liv) && !empty($forsakring_liv))
        {
            $forsakringar[] = $forsakring_liv;
        }

        $horse_name = $data['hasten']['namn'] ?? '';
        $horse_usage = $focus_data['horse_usage'];
        $horse_usage_label = $data['sammanfattning']['forsakring'] ?? '';

        return view('steps.horseinsurance.sammanfattning', [
            'data'              => $data,
            'price'             => $price,
            'forsakringar'      => $forsakringar,
            'horse_name'        => $horse_name,
            'horse_usage'       => $horse_usage,
            'horse_usage_label' => $horse_usage_label,
        ]);
    }

    public function validateStep(Request $request)
    {

        $focusapi = new FocusApi();
        $focus_data = $focusapi->get_shared_focus_data();

        $horse_usage = $focus_data['horse_usage'];

        $input = [
            'startdatum'        => request()->get('startdatum'),
            'email'             => request()->get('email'),
            'telefon'           => request()->get('telefon'),
            'betalningsmetod'   => request()->get('betalningsmetod'),
            'betalningstermin'  => request()->get('betalningstermin'),
            'autogiro_clearing' => request()->get('autogiro_clearing'),
            'autogiro_account'  => request()->get('autogiro_account'),
            'chip_number'       => request()->get('chip_number'),
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
        ];

        $validation_messages = [
            'startdatum.required'       => 'Du måste välja ett giltligt startdatum.',
            'startdatum.date'           => 'Du måste välja ett giltligt startdatum.',
            'startdatum.after_or_equal' => 'Startdatumet får inte vara tidigare än idag.',
            'startdatum.before'         => 'Startdatumet får inte vara längre än 90 dagar fram.',
        ];

        if (isset($horse_usage) && $horse_usage == 2)
        {
            $rules['startdatum'] = 'required|date:Y-m-d|after_or_equal:' . today()->format('Y-m-d') . '|before:' . today()->addDays(40)->format('Y-m-d');
            $validation_messages['startdatum.before'] = 'Startdatumet får inte vara längre än 40 dagar fram.';
        }
        else
        {
            $rules['startdatum'] = 'required|date:Y-m-d|after_or_equal:' . today()->format('Y-m-d') . '|before:' . today()->addDays(90)->format('Y-m-d');
            $validation_messages['startdatum.before'] = 'Startdatumet får inte vara längre än 90 dagar fram.';
        }

        if ($horse_usage == 2)
        {
            $rules['betalningsmetod'] = 'required|in:faktura';
            //unset($rules['chip_number']);
        }

        // ung o ridhäst, remove chip_number as requirement
        if ($horse_usage == 6)
        {
            unset($rules['chip_number']);
        }

        $validator = Validator::make($input, $rules, $validation_messages);

        // Validate bankaccount against focus api
        $validator->after(function ($validator) use ($focusapi, &$input) {

            // Validera telefonnummer
            try
            {
                $input['telefon'] = PhoneNumber::make($input['telefon'], 'SE')->formatE164();
            }
            catch (\Exception $e)
            {
                $validator->errors()->add('telefon', 'Du måste ange korrekt format på telefonnumret');
            }

            if (isset($input['betalningsmetod']) && $input['betalningsmetod'] == 'autogiro')
            {
                try
                {
                    $focus_autogiro_response = $focusapi->valid_autogiro_account($input['autogiro_clearing'], $input['autogiro_account']);
                    if (!isset($focus_autogiro_response['data']) || $focus_autogiro_response['data'] != 1)
                    {
                        $validator->errors()->add('autogiro_clearing', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                        $validator->errors()->add('autogiro_account', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                        $validator->errors()->add('autogiro_error', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    }
                }
                catch (FocusApiException $e)
                {
                    $validator->errors()->add('autogiro_clearing', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    $validator->errors()->add('autogiro_account', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                    $validator->errors()->add('autogiro_error', 'Det verkar som att du angivit ett felaktigt clearing- eller kontonummer.');
                }
            }
        });

        if ($validator->fails())
        {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray(),
            ];

            return response()->json($response);
        }

        // Store data
        $this->store_data($input);

        return response()->json([
            'status'    => 1,
            'next_step' => '',
        ]);
    }

    public function build_data($focusapi, $focus_data)
    {

        //$focus_customer = $focusapi->get_customer($focus_data['civic_number']);
        $focus_address = $focusapi->get_address($focus_data['civic_number']);

        // Get moment
        $focus_moments = collect($focusapi->get_moment(22));

        $veterinarvardsforsakring = '';
        if ((isset($focus_data['veterinarvardsforsakring']) && !empty($focus_data['veterinarvardsforsakring'])) && in_array($focus_data['veterinarvardsforsakring'], $focus_data['forsakring_enabled']))
        {
            $veterinarvardsforsakring = $focus_moments->where('id', $focus_data['veterinarvardsforsakring'])->first()['namn'] ?? '';
        }
        $livforsakring = '';
        if ((isset($focus_data['livforsakring']) && !empty($focus_data['livforsakring'])) && in_array($focus_data['livforsakring'], $focus_data['forsakring_enabled']))
        {
            $livforsakring = $focus_moments->where('id', $focus_data['livforsakring'])->first()['namn'] ?? '';
        }

        $data = [
            'sammanfattning' => [
                'forsakring'               => $focus_data['horse_usage_label'] ?? 'Försäkring',
                'veterinarvardsforsakring' => $veterinarvardsforsakring,
                'livforsakring'            => $livforsakring,
                'livvarde'                 => $focus_data['livvarde'] ?? null,
                'sjalvrisk'                => $focus_data['sjalvrisk'] ?? null,
                'veterinarvardsbelopp'     => $focus_data['veterinarvardsbelopp'] ?? null,
                'startdatum'               => $focus_data['startdatum'] ?? today()->format('Y-m-d'),
                'forsakring_enabled'       => $focus_data['forsakring_enabled'] ?? [],
                'uppsagning'               => $focus_data['uppsagning'] ?? null,
            ],
            'hasten'         => [
                'namn'                => $focus_data['namn'] ?? '',
                'fodelsedatum'        => $focus_data['fodelsedatum'] ?? null,
                'registreringsnummer' => $focus_data['registration_number'] ?? null,
                'chipnummer'          => $focus_data['chip_number'] ?? null,
            ],
            'kund'           => [
                'namn'              => ($focus_address['fornamn'] ?? '') . ' ' . ($focus_address['efternamn'] ?? ''),
                'personnummer'      => $focus_address['persnr'] ?? '',
                'adress_gata'       => $focus_address['adress'] ?? '',
                'adress_postnummer' => $focus_address['postnr'] ?? '',
                'adress_ort'        => $focus_address['ort'] ?? '',
                'email'             => (!empty($focus_address['email']) ? $focus_address['email'] : $focus_data['email'] ?? ''),
                'telefon'           => (!empty($focus_address['mobil']) ? $focus_address['mobil'] : $focus_data['telefon'] ?? ''),
                'betalningsmetod'   => $focus_data['betalningsmetod'] ?? '',
                'betalningstermin'  => $focus_data['betalningstermin'] ?? '',
            ],
        ];

        return $data;
    }

    public function bankid_sign()
    {

        // Check validation during signing as well
        $validation = $this->validateStep(request())->getData('true');
        if (!isset($validation['status']) || $validation['status'] !== 1)
        {
            return $validation;
        }

        $focusapi = new FocusApi();
        $focus_data = $focusapi->get_shared_focus_data();

        // Get civic number from session
        $civic_number = $focus_data['civic_number'] ?? '';

        // Sign
        try
        {
            // start bankid transaction
            $bankid_sign_text = 'Jag godkänner härmed köp av försäkring hos Dunstan AB';
            $data = $focusapi->bankid_sign($civic_number, $bankid_sign_text, '', 1);

            $response = [
                'status'   => 1,
                'orderRef' => $data['orderRef'],
            ];
        }
        catch (FocusApiException $e)
        {
            report($e);
            $error_text = 'Ett okänt fel har inträffat';
            if ($e->getCode() == 400)
            {
                $error_json = json_decode($e->getMessage(), true);
                $error_text = $error_json['message'] ?? $error_text;
            }

            $response = [
                'status'  => 0,
                'message' => $error_text,
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
        $focusapi = new FocusApi();

        if ($force_retries != $conf_bankid_force_retries)
        {
            $attempt = $conf_bankid_force_retries - $force_retries;

            $this->log_data('Retrying to get BankID status, attempt ' . $attempt . '.', [
                'orderRef' => $orderRef,
            ], 'warning');
        }

        if (session()->has('bankid.' . $orderRef) && session()->get('bankid.' . $orderRef) == 'COMPLETE')
        {
            $response = [
                'status'    => 1,
                'next_step' => 'tack',
            ];

            session()->forget('bankid');

            return $response;
        }

        // Check sign status
        try
        {

            // start bankid status check
            $data = $focusapi->bankid_login_check($orderRef);
            $bankid_status = $data['status'];
        }
        catch (FocusApiException $e)
        {
            report($e);
            $error_text = 'Ett okänt fel har inträffat';
            if ($e->getCode() == 400)
            {
                $error_json = json_decode($e->getMessage(), true);
                $error_text = $error_json['message'] ?? $error_text;
            }

            $response = [
                'status'  => 0,
                'message' => $error_text,
            ];

            return $response;
        }

        $pending_statuses = [
            'OUTSTANDING_TRANSACTION',
            'STARTED',
            'USER_SIGN',
        ];

        if (in_array($bankid_status, $pending_statuses))
        {
            $response = [
                'status'  => 2,
                'message' => 'Polling bankid status..',
            ];

            return $response;
        }

        // Send to focus
        if ($bankid_status == 'COMPLETE')
        {
            $focus_data = $focusapi->get_shared_focus_data();

            try
            {

                // Log data after bankid have been signed, this is done before anything is sent to focus.
                $this->log_data('Bankid signed. Shared data.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data'       => $focus_data,
                ]);

                $this->send_focus_data();

                session()->put('bankid', [
                    $orderRef => $bankid_status,
                ]);

                $response = [
                    'status'    => 1,
                    'next_step' => 'tack',
                ];
            }
            catch (FocusApiException $e)
            {
                report($e);

                // Logging
                $this->log_data('Sending data to focus failed.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data'       => $focus_data,
                ], 'error');

                $response = [
                    'status'  => 0,
                    'message' => 'Ett fel har inträffat.',
                ];
            }
        }
        else
        {
            $response = [
                'status'         => 0,
                'message'        => 'Ett fel har inträffat vid bankid status check.',
                'focus_response' => $data,
            ];

            // Logging
            $this->log_data('BankID status check failed.', [
                'orderRef' => $orderRef,
                'data'     => $response,
            ], 'error');
        }

        return $response;
    }

    // Send all data to focus
    public function send_focus_data()
    {
        $focusapi = new FocusApi();

        // Hämta samlad data
        $data = $focusapi->get_shared_focus_data();

        // Update or create customer with details
        $customer_data = [];
        if (isset($data['email']) && !empty($data['email']))
        {
            $customer_data['email'] = $data['email'];
        }
        if (isset($data['telefon']) && !empty($data['telefon']))
        {
            $customer_data['mobil'] = $data['telefon'];
            $customer_data['telefon'] = $data['telefon'];
        }

        // First get customer
        $create_customer = false;
        try
        {
            // get customer
            $focus_customer_response = $focusapi->get_customer($data['civic_number']);
        }
        catch (FocusApiException $e)
        {
            // probably means customer doesnt exist, create it?
            $create_customer = true;
        }

        // Logging
        $this->log_data('Customer data. ', [
            'session_id' => $data['session_id'] ?? null,
            'data'       => $customer_data,
        ]);

        if ($create_customer)
        {
            // create customer
            $focus_customer = $focusapi->create_customer($data['civic_number'], $customer_data);
        }
        else
        {
            // update customer
            $focus_customer = $focusapi->update_customer($data['civic_number'], $customer_data);
        }

        // Get customer id
        $customer_id = $focus_customer['id'];

        // Om betalning är autogiro, registrera autogiro här
        if (isset($data['betalningsmetod']) && $data['betalningsmetod'] == 'autogiro')
        {
            $payment_clearing_number = $data['autogiro_clearing'];
            $payment_account_number = $data['autogiro_account'];
            try
            {
                $focusapi->register_autogiro($customer_id, $payment_clearing_number, $payment_account_number);

                // Logging
                $this->log_data('Stored autogiro to focus.', [
                    'session_id'  => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data'        => [
                        'clearing_number' => $payment_clearing_number ?? null,
                        'account_number'  => $payment_account_number ?? null,
                    ],
                ]);
            }
            catch (FocusApiException $e)
            {
                report($e);

                // Logging
                $this->log_data('Could not store autogiro to focus.', [
                    'session_id'  => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data'        => [
                        'clearing_number' => $payment_clearing_number ?? null,
                        'account_number'  => $payment_account_number ?? null,
                    ],
                ], 'error');
            }
        }

        // Spara hälsodeklarationen
        $focus_questions_svarande_id = null;
        // temp fix, to be removed
        $horse_usage = $data['horse_usage'];
        if ($horse_usage != 2)
        { // remove
            if (isset($data['document_type']) && !empty($data['document_type']))
            {
                try
                {
                    $focus_questions_response = $focusapi->save_questions($customer_id, $data['questions'], $data['document_type'], 0);
                    $focus_questions_svarande_id = $focus_questions_response['svarande'] ?? null;

                    // Logging
                    $this->log_data('Stored document to focus.', [
                        'session_id'    => $data['session_id'] ?? null,
                        'customer_id'   => $customer_id ?? null,
                        'document_type' => $data['document_type'] ?? null,
                        'data'          => $data['questions'] ?? null,
                    ]);
                }
                catch (FocusApiException $e)
                {
                    report($e);
                    $focus_questions_svarande_id = null;

                    // Logging
                    $this->log_data('Could not store Document to focus.', [
                        'session_id'    => $data['session_id'] ?? null,
                        'customer_id'   => $customer_id ?? null,
                        'document_type' => $data['document_type'] ?? null,
                        'data'          => $data['questions'],
                    ], 'error');
                }
            }
        } // remove

        // Fakturastatus,
        $invoice_status = null;
        // Check if risk, born = import, except yes means born in sweden
        if (isset($data['born_risk']) && $data['born_risk'] == 1)
        {
            $invoice_status = 'Risk';
        }

        // Lägg till notering om foster & föl
        $notes = null;
        if (isset($data['horse_usage']) && $data['horse_usage'] == 2)
        {
            $notes .= 'Beräknat datum för föling: ';
            if (isset($data['folningdatum']) && !empty($data['folningdatum']))
            {
                $notes .= $data['folningdatum'];
            }
            else
            {
                $notes .= 'Saknar datum';
            }
            $notes .= ', Stoets namn: ';
            if (isset($data['namn']) && !empty($data['namn']))
            {
                $notes .= $data['namn'];
            }
            else
            {
                $notes .= 'Saknar namn';
            }
        }

        // Lägg till notering om uppsägning
        if (!empty($data['uppsagning']))
        {
            $notes .= 'Kunden har begärt hjälp med att säga upp befintlig försäkring för hästen "' . $data['namn'] . '".';
        }

        // Anslut försäkring
        $moments = [];
        if (!empty($data['veterinarvardsforsakring']) && array_key_exists('vet', $data['forsakring_enabled']))
        {
            $moments[] = $data['veterinarvardsforsakring'];
        }
        if (!empty($data['livforsakring']) && $data['veterinarvardsforsakring'] != $data['livforsakring'] && array_key_exists('liv', $data['forsakring_enabled']))
        {
            $moments[] = $data['livforsakring'];
        }

        $moments_ids = implode(',', $moments);
        $focus_moments_fields = $focusapi->build_focus_fields($moments, $data);

        // Logging
        $this->log_data('Adding insurance, sent to focus.', [
            'session_id'           => $data['session_id'] ?? null,
            'customer_id'          => $customer_id ?? null,
            'moment_ids'           => $moments_ids ?? null,
            'fields'               => $focus_moments_fields ?? null,
            'question_response_id' => $focus_questions_svarande_id ?? null,
            'payment_terms'        => $data['betalningstermin'] ?? null,
            'start_date'           => $data['startdatum'] ?? null,
            'invoice_status'       => $invoice_status ?? null,
            'notes'                => $notes ?? null,
        ]);

        $focus_moments_response = $focusapi->add_insurance(
            $customer_id,
            $moments_ids,
            $focus_moments_fields,
            $focus_questions_svarande_id,
            8,
            $data['betalningstermin'],
            null,
            $data['startdatum'],
            $invoice_status,
            $notes
        );

        // Add notering to customer
        if (isset($notes) && !empty($notes))
        {
            try
            {
                $focus_notering_response = $focusapi->add_note_to_customer($customer_id, $notes);

                // Logging
                $this->log_data('Notes', [
                    'session_id'  => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data'        => $notes ?? null,
                ]);
            }
            catch (FocusApiException $e)
            {
                report($e);

                // Logging
                $this->log_data('Could not add note to focus.', [
                    'session_id'  => $data['session_id'] ?? null,
                    'customer_id' => $customer_id ?? null,
                    'data'        => $notes ?? null,
                ], 'error');
            }
        }

        /*
        echo '<pre>'.print_r($focus_moments_response, true).'</pre>';
        die();
        */

        // Add points
        $total_utpris = 0;
        $points = 0;
        $products = [];

        try
        {
            if (isset($focus_moments_response['utpris']))
            {
                $total_utpris += $focus_moments_response['utpris'];
                $products[$focus_moments_response['momentId']] = ['total' => $focus_moments_response['utpris']];
            }
            else
            {
                foreach ($focus_moments_response as $moment)
                {
                    if (isset($moment['utpris']))
                    {
                        $total_utpris += $moment['utpris'];
                        $products[$moment['momentId']] = ['total' => $moment['utpris']];
                    }
                }
            }
        }
        catch (FocusApiException $e)
        {
            report($e);

            // Logging
            $this->log_data('Could not handle response from focus when calculating price.', [
                'session_id'  => $data['session_id'] ?? null,
                'customer_id' => $customer_id ?? null,
            ], 'error');
        }

        // under 5000kr = 200p, över 5000kr = 400p
        try
        {
            if ($total_utpris > 5000)
            {
                $points = 400;
                $focus_points_response = $focusapi->add_points($customer_id, $points, $data['namn']);
            }
            else
            {
                $points = 200;
                $focus_points_response = $focusapi->add_points($customer_id, $points, $data['namn']);
            }

            // Logging
            $this->log_data('Added points to account.', [
                'session_id'  => $data['session_id'] ?? null,
                'customer_id' => $customer_id ?? null,
                'data'        => $points ?? null,
            ]);
        }
        catch (FocusApiException $e)
        {
            report($e);

            // Logging
            $this->log_data('Could not add points to account.', [
                'session_id'  => $data['session_id'] ?? null,
                'customer_id' => $customer_id ?? null,
            ], 'error');
        }

        // Create woocommerce users
        if (isset($data['email']) && !empty($data['email']))
        {

            if (config('services.woocommerce.create_user'))
            {
                $woocommerceapi = new WoocommerceApi();

                $nickname = $data['civic_number'] . '-' . $customer_id;

                try
                {
                    $woo_user_response = $woocommerceapi->create_user($data['email'], null, $nickname,);

                    // Logging
                    $this->log_data('Created user at wordpress instance.', [
                        'session_id'  => $data['session_id'] ?? null,
                        'customer_id' => $customer_id ?? null,
                        'email'       => $data['email'] ?? null,
                        'nickname'    => $nickname ?? null,
                    ]);
                }
                catch (WoocommerceApiException $e)
                {
                    // Silent error?
                    report($e);

                    // Logging
                    $this->log_data('Could not create user at wordpress instance.', [
                        'session_id'  => $data['session_id'] ?? null,
                        'customer_id' => $customer_id ?? null,
                        'email'       => $data['email'] ?? null,
                        'nickname'    => $nickname ?? null,
                    ], 'error');
                }
            }

            // Mailchimp
            try
            {

                $mailchimp_tags = [
                    'LEADfranKonfig',
                    'LeadWebHastNY',
                ];
                if (isset($data['step_insurance']) && $data['step_insurance'] == 'hastforsakring-b-1')
                {
                    $mailchimp_tags = [
                        'LEADfranKonfig',
                        'LeadWebHastJAMFOR',
                    ];
                }

                // Meta data
                $meta = [];
                if (isset($focus_customer['fornamn']) && !empty($focus_customer['fornamn']))
                {
                    $meta['FNAME'] = $focus_customer['fornamn'];
                }
                if (isset($focus_customer['efternamn']) && !empty($focus_customer['efternamn']))
                {
                    $meta['LNAME'] = $focus_customer['efternamn'];
                }
                // hästens namn
                if (isset($data['namn']) && !empty($data['namn']))
                {
                    $meta['MMERGE9'] = $data['namn'];
                }
                // Hästens födelsedata
                if (isset($data['fodelsedatum']) && !empty($data['fodelsedatum']))
                {
                    try
                    {
                        $meta['MMERGE11'] = \Carbon\Carbon::parse($data['fodelsedatum'])->format('d/m/Y');
                    }
                    catch (\Exception $e)
                    {
                        // silent error
                        unset($meta['MMERGE11']);
                    }
                }

                $this->log_data('Mailchimp metadata.', [
                    'session_id' => $focus_data['session_id'] ?? null,
                    'data'       => [
                        'meta' => $meta,
                        'tags' => $mailchimp_tags,
                    ],
                ]);

                $mailchimpapi = new MailchimpApi;
                $mailchimpapi->subscribe_member($data['email'], $meta);
                $mailchimpapi->member_assign_tags($data['email'], $mailchimp_tags);
            }
            catch (\Exception $e)
            {
                report($e);
            }
        }

        // Send mail
        try
        {
            $send_email_to = null;
            if (config('services.focus.live')) {
                $send_email_to = config('services.dunstan.email_live');
            } else {
                $send_email_to = config('services.dunstan.email_test');
            }

            // Dunstan
            Mail::to($send_email_to)->send(new BookingMail('Nytecknad försäkring via web', [
                    'kundnr'    => $focus_customer['kundnr'] ?? '',
                    'fornamn'   => $focus_customer['fornamn'] ?? '',
                    'efternamn' => $focus_customer['efternamn'] ?? '',
                ]));
        }
        catch (\Exception $e)
        {
            // Silent error if mail fails
            report($e);
        }

        // Store data to tack sida for google ecommerce
        $this->store_data([
            'products' => $products,
        ], 'tack');
    }

    public function get_price($defaults = null)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        if ($data['horse_usage'] == 2)
        {
            $focus_moments = collect($focusapi->get_moment(26));
        }
        else
        {
            // get moment
            $focus_moments = collect($focusapi->get_moment(22));
        }

        if (isset($data['veterinarvardsforsakring']) && !empty($data['veterinarvardsforsakring']))
        {
            $data['veterinarvardsforsakring_label'] = $focus_moments->where('id', $data['veterinarvardsforsakring'])->first()['namn'] ?? '';
        }
        if (isset($data['livforsakring']) && !empty($data['livforsakring']))
        {
            $data['livforsakring_label'] = $focus_moments->where('id', $data['livforsakring'])->first()['namn'] ?? '';
        }

        $moments = [];
        if (!empty($data['veterinarvardsforsakring']) && array_key_exists('vet', $data['forsakring_enabled']))
        {
            $moments[] = $data['veterinarvardsforsakring'];
        }
        if (!empty($data['livforsakring']) && $data['veterinarvardsforsakring'] != $data['livforsakring'] && array_key_exists('liv', $data['forsakring_enabled']))
        {
            $moments[] = $data['livforsakring'];
        }

        $focus_fields = $focusapi->build_focus_fields($moments, $data);

        try
        {
            $termin = 1;
            // Foster och föl 12 månader
            if (isset($data['horse_usage']) && $data['horse_usage'] == 2)
            {
                $termin = 12;
            }
            $focus_price_response = $focusapi->get_pris(implode(',', $moments), $focus_fields, $data['civic_number'], $termin);

            $total_utpris = 0;
            $total_utpris_formated = 0;
            $total_total_utpris = 0;
            $total_total_formated_utpris = 0;
            $points = 0;

            // Monthly nad yearly
            if (isset($focus_price_response['utpris_per_termin']))
            {
                $total_utpris = $focus_price_response['utpris_per_termin'];
                $total_total_utpris = $focus_price_response['utpris'];
            }
            else
            {
                foreach ($focus_price_response as $utpris)
                {
                    if (isset($utpris['utpris_per_termin']))
                    {
                        $total_utpris += $utpris['utpris_per_termin'];
                        $total_total_utpris += $utpris['utpris'];
                    }
                }
            }

            $total_utpris_formated = number_format($total_utpris, 0, ',', ' ') . ' kr/' . (($termin == 1) ? 'mån' : 'år');
            $total_total_formated_utpris = number_format($total_total_utpris, 0, ',', ' ');

            if ($total_total_utpris > 5000)
            {
                $points = 400;
            }
            else
            {
                $points = 200;
            }
        }
        catch (FocusApiException $e)
        {
            report($e);
            $total_utpris_formated = '-';
            $total_total_formated_utpris = '-';
            $total_utpris = 0;
            $total_total_utpris = 0;
            $points = 0;
        }

        $compare_insurance = $data['compare_insurance'] ?? null;

        return [
            'html'              => view('steps.horseinsurance.resultat.pris', [
                'data'                => $data,
                'utpris'              => $total_utpris_formated,
                'utpris_formaterad'   => $total_utpris_formated,
                'points'              => $points,
                'disabled_checkboxes' => true,
                'compare_insurance'   => $compare_insurance,
            ])->render(),
            'utpris'            => $total_utpris,
            'utpris_formaterad' => $total_utpris_formated,
            'points'            => $points,
        ];
    }

    private function log_data($message, $data = [], $type = 'info')
    {
        Log::channel('dunstan')->{$type}($message, ['data' => $data]);
    }
}
