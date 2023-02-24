<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Libraries\Mailchimp\MailchimpApiException;
use App\Models\Contacts;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B12 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-12';
    public $progressbar = 95;
    public $skipable = true;

    public function view(Request $request)
    {

        // Fetch session data
        $email = $this->get_data($this->name.'.email');
        $telefon = $this->get_data($this->name.'.telefon');

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-b-6.namn');

        return view('steps.horseinsurance.a.a10', [
            'email' => $email,
            'telefon' => $telefon,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'email' 	=> $request->get('email'),
            'telefon' 	=> $request->get('telefon'),
            'skip'  	=> $request->get('skip', 0)
        ];

        $rules = [
            'email'		=> 'required|email',
            'telefon'		=> 'required'
        ];

        // Skip
        if($input['skip'] == 1 && $this->skipable){
            $input['email'] = null;
            $input['telefon'] = null;

        // Validate
        } else {
            $validator = Validator::make($input, $rules);

            if($validator->fails()){
                $response = [
                    'status' => 0,
                    'errors' => $validator->errors()->toArray()
                ];
                return response()->json($response);
            }

        }

        // Insert into database
        if(isset($input['email']) && !empty($input['email'])){
            // get step session id
            $step_session_id = $request->session()->get('steps.session_id', null);
            $contact = Contacts::firstOrNew([
                'uuid' => $step_session_id
            ]);
            $contact->uuid = $step_session_id;
            $contact->email = $input['email'];
            $contact->save();

            $merge_tags = [
            	'PHONE' => $input['telefon'] ?? null,
            ];

            try {
                $mailchimpapi = new MailchimpApi;
                $mailchimpapi->subscribe_member($input['email'], $merge_tags);
                $mailchimpapi->member_assign_tags($input['email'], ['LEADfranKonfig', 'LeadWebHastJAMFOR']);
            } catch (\Exception $e){
                report($e);
            }
            
        }

        // Store data
        $this->store_data($input);

        $next_step = $this->skip_to_next_available_step($this->name);

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
