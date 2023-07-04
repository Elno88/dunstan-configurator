<?php

namespace App\Steps\Farminsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Libraries\Mailchimp\MailchimpApiException;
use App\Mail\GardsforsakingContact;
use App\Mail\GardsforsakingOffert;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class Kontakt extends StepAbstract
{
    public $name = 'gardsforsakring-kontakt';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {
        // Get data
        $farminsurance_step = $this->get_data('gardsforsakring.gardsforsakring') ?? 'gardsforsakring-a-1';
        $pdf = $this->get_data('gardsforsakring-b-2.pdf_data') ?? null;

        if ($farminsurance_step == 'gardsforsakring-a-1') {
            $address_street = $this->get_data('gardsforsakring-a-1.street') ?? '';
            $address_zip = $this->get_data('gardsforsakring-a-1.zip') ?? '';
            $address_city = $this->get_data('gardsforsakring-a-1.city') ?? '';
        } else {
            $address_street = $this->get_data('gardsforsakring-b-1.street') ?? '';
            $address_zip = $this->get_data('gardsforsakring-b-1.zip') ?? '';
            $address_city = $this->get_data('gardsforsakring-b-1.city') ?? '';
        }

        return view('steps.farminsurance.kontakt', [
            'farminsurance_step' => $farminsurance_step,
            'address_street'     => $address_street,
            'address_zip'        => $address_zip,
            'address_city'       => $address_city,
            'pdf'                => $pdf
        ]);
    }

    public function validateStep(Request $request)
    {
        $farminsurance_step = $this->get_data('gardsforsakring.gardsforsakring') ?? 'gardsforsakring-a-1';

        $input = [
            'email'      => $request->get('email'),
            'firstname'  => $request->get('firstname'),
            'lastname'   => $request->get('lastname'),
            'zip'        => $request->get('zip'),
            'street'     => $request->get('street'),
            'city'       => $request->get('city'),
            'phone'      => $request->get('phone'),
            'phone_time' => $request->get('phone_time'),
            'term'       => $request->get('term'),
        ];

        $rules = [
            'email'      => 'required|email',
            'phone'      => 'nullable',
            'phone_time' => 'nullable',
            'firstname'  => 'required',
            'lastname'   => 'required',
            'street'     => 'required',
            'zip'        => 'required',
            'city'       => 'required',
            'term'       => 'required',
        ];

        if (isset($input['phone']) && !empty($input['phone'])) {
            $rules['phone'] = 'required';
            $rules['phone_time'] = 'required';
        }

        $validator = Validator::make($input, $rules);

        // Validate bankaccount against focus api
        $validator->after(function ($validator) use (&$input) {
            // Validera telefonnummer
            if (isset($input['phone']) && !empty($input['phone'])) {
                try {
                    $input['phone'] = PhoneNumber::make($input['phone'], 'SE')->formatE164();
                } catch (\Exception $e) {
                    $validator->errors()->add('phone', 'Du måste ange korrekt format på telefonnumret');
                }
            }
        });

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        $this->store_data($input);

        // Send to focus
        try {

            if ($farminsurance_step == 'gardsforsakring-a-1') {
                // a, new
                $this->send_contact($input);
            } else {
                // b, insurley
                $this->send_focus_data();
            }
        } catch (FocusApiException $e) {
            report($e);
            throw $e;
        }

        // Add to mailchimp
        try {
            // meta first and lastname, address
            $meta = [
                'FNAME'   => $input['firstname'] ?? '',
                'LNAME'   => $input['lastname'] ?? '',
                'ADDRESS' => [
                    'addr1' => $input['street'] ?? '',
                    'addr2' => '',
                    'zip'   => $input['zip'] ?? '',
                    'city'  => $input['city'] ?? '',
                    'state' => '',
                ]
            ];

            // Phone
            if (isset($input['phone']) && !empty($input['phone'])) {
                $meta['PHONE'] = $input['phone'];
                $meta['TELEFONTID'] = $input['phone_time'] ?? '';
            }

            $mailchimpapi = new MailchimpApi;
            $mailchimpapi->subscribe_member($input['email'], $meta);

            if ($farminsurance_step == 'gardsforsakring-a-1') {
                // manual
                $mailchimpapi->member_assign_tags($input['email'], ['Offertförfrågan WEB', 'LeadGardKONTAKTA']);
            } else {
                // jämför
                $mailchimpapi->member_assign_tags($input['email'], ['Offertförfrågan WEB', 'LeadGardJAMFOR']);
            }
        } catch (MailchimpApiException $e) {
            report($e);
        }

        $response = [
            'status'    => 1,
            'next_step' => 'gardsforsakring-tack'
        ];

        return response()->json($response);
    }

    public function send_contact($data)
    {
        try {
            $send_emails_to = [];

            if (config('services.focus.live')) {
                $send_email_to[] = config('services.dunstan.email_live');
                $send_email_to[] = 'daniel.lazic@convertor.se';
                $send_email_to[] = 'info@dunstan.se';
                $send_email_to[] = 'petru.gilezan@dunstan.se';
                $send_email_to[] = 'klas.redemo@dunstan.se';
                $send_email_to[] = 'pernilla.ivarsson@dunstan.se';
                $send_email_to[] = 'christine.wikstrand@dunstan.se';
            } else {
                $send_email_to[] = config('services.dunstan.email_test');
            }

            foreach ($send_email_to as $email_to) {
                Mail::to($email_to)->send(new GardsforsakingContact(
                    'Intresseanmälan Gårdsförsäkring',
                    $data
                ));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function send_focus_data()
    {
        $data = [
            'insurances'   => $this->get_data('gardsforsakring-b-2.insurances') ?? [],
            'civic_number' => $this->get_data('gardsforsakring-b-2.civic_number') ?? null,
            'email'        => $this->get_data('gardsforsakring-kontakt.email') ?? null,
            'phone'        => $this->get_data('gardsforsakring-kontakt.phone') ?? null,
            'phone_time'   => $this->get_data('gardsforsakring-kontakt.phone_time') ?? null,
        ];

        // Generate pdf file from insurley insurance data
        $pdf_file = base64_decode($this->get_data('gardsforsakring-b-2.pdf_data') ?? '');

        $focusapi = new FocusApi();

        // Update or create customer with details
        $customer_data = [];

        if (isset($data['email']) && !empty($data['email'])) {
            $customer_data['email'] = $data['email'];
        }
        if (isset($data['phone']) && !empty($data['phone'])) {
            $customer_data['mobil'] = $data['phone'];
            $customer_data['telefon'] = $data['phone'];
        }

        // First get customer
        $create_customer = false;
        try {
            $focus_customer_response = $focusapi->get_customer($data['civic_number']);
        } catch (FocusApiException $e) {
            // probably meants customer doesnt exist, create it?
            $create_customer = true;
        }

        if ($create_customer) {
            $focus_customer = $focusapi->create_customer($data['civic_number'], $customer_data);
        } else {
            $focus_customer = $focusapi->update_customer($data['civic_number'], $customer_data);
        }

        $customer_id = $focus_customer['id'];

        $comment = 'Web';

        if (isset($data['phone']) && !empty($data['phone'])) {
            $comment = 'Kund önskar att bli kontaktad mellan kl: ' . ($data['phone_time'] ?? '');
        }

        $focus_ticket = $focusapi->create_ticket($customer_id, 'Offertunderlag gård', $comment);
        $focus_ticket_id = $focus_ticket['id'] ?? null;

        if (!empty($focus_ticket_id)) {
            try {
                $encoded_file = 'data:application/pdf;base64,' . base64_encode($pdf_file);
                $focus_ticket_file = $focusapi->add_ticket_file($focus_ticket_id, 'Insurely.pdf', $encoded_file);
            } catch (FocusApiException $e) {
                report($e);
            }
        }

        try {
            $send_emails_to = [];

            if (config('services.focus.live')) {
                $send_email_to[] = config('services.dunstan.email_live');
                $send_email_to[] = 'daniel.lazic@convertor.se';
                $send_email_to[] = 'info@dunstan.se';
                $send_email_to[] = 'petru.gilezan@dunstan.se';
                $send_email_to[] = 'klas.redemo@dunstan.se';
                $send_email_to[] = 'pernilla.ivarsson@dunstan.se';
                $send_email_to[] = 'christine.wikstrand@dunstan.se';
            } else {
                $send_email_to[] = config('services.dunstan.email_test');
            }

            foreach ($send_email_to as $email_to) {
                Mail::to($email_to)->send(new GardsforsakingOffert(
                    'Offertunderlag gård',
                    [],
                    $pdf_file
                ));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}
