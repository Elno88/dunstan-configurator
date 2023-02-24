<?php namespace App\Steps\Horseinsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Mail\Contact;
use App\Mail\ContactConfirm;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;
use Mail;

class Kontakt extends StepAbstract
{
    public $name = 'kontakt';
    public $progressbar = 100;
    public $skipable = false;

    public function view(Request $request)
    {
        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $horse_name = $data['namn'] ?? '';
        $horse_usage_label = $data['horse_usage_label'] ?? 'Försäkring';

        return view('steps.horseinsurance.kontakt', [
            'horse_name' => $horse_name,
            'horse_usage_label' => $horse_usage_label
        ]);

    }

    public function validateStep(Request $request)
    {

        // input
        $input = [
            'fornamn'       => $request->get('fornamn'),
            'efternamn'     => $request->get('efternamn'),
            'epostadress'   => $request->get('epostadress'),
            'telefonnr'     => $request->get('telefonnr'),
            'term'          => $request->get('term'),
        ];

        // rules
        $rules = [
            'fornamn'       => 'required',
            'efternamn'     => 'required',
            'epostadress'   => 'required|email',
            'telefonnr'     => 'required',
            'term'          => 'required',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        // Store data
        $this->store_data($input);

        $send_email_to = null;
        if(config('services.focus.live')){
            $send_email_to = config('services.dunstan.email_live');
        } else {
            $send_email_to = config('services.dunstan.email_test');
        }

        // Send email
        try {
            if(!empty($send_email_to)){
                Mail::to($send_email_to)->send(new Contact(
                    'Prisförfrågan Foster & Föl',
                    $input
                ));

                Mail::to($input['epostadress'])->send(new ContactConfirm(
                    'Prisförfrågan Foster & Föl - Bekräftelse',
                    $input
                ));
            }
        } catch (\Exception $e) {
            report($e);
            $response = [
                'status' => 0,
                'error_email' => 'Vi kunde tyvärr inte skicka ut ett e-postmeddelande.'
            ];
            return response()->json($response);
        }

        return response()->json([
            'status' => 1,
            'next_step' => 'kontakt-tack'
        ]);

    }

}
