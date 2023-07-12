<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Libraries\Papilite\PapiliteApi;
use App\Libraries\Papilite\PapiliteApiException;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B11 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-11';
    public $progressbar = 87;

    public function view(Request $request)
    {

        // Fetch session data
        $civic_number = $this->get_data($this->name.'.civic_number');

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-b-6.namn');

        return view('steps.horseinsurance.a.a9', [
            'civic_number' => $civic_number,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {

        // Focus Api
        $focusapi = new FocusApi();

        $input = [
            'civic_number' => preg_replace('/\D/', '', $request->get('civic_number')),
            'state' => ''
        ];

        $rules = [
            'civic_number'        => 'required'
        ];

        $validator = Validator::make($input, $rules);

        $focus_address_response = [];

        // Validate civic_number
        $validator->after(function ($validator) use (&$input, $focusapi, &$focus_address_response){

            // Validate the numbers somewhat
            if(!empty($input['civic_number'])){
                // check if not 12 or not numeric
                if(strlen($input['civic_number']) != 12 || !is_numeric($input['civic_number'])){
                    $validator->errors()->add('civic_number', 'Du måste ange ett giltligt personnummer.');
                } else {
                    // Check if first 8 chars is a valid date and haven't passed todays date
                    // Parse first 8 as date
                    $date = substr($input['civic_number'], 0, 8);
                    try {
                        $date_parsed = \Carbon\Carbon::parse($date);
                        // if date is higher then today, not valid
                        if($date_parsed->gt(today())){
                            $validator->errors()->add('civic_number', 'Du måste ange ett giltligt personnummer.');
                        }
                    } catch (\Exception $e) {
                        // Not valid date
                        $validator->errors()->add('civic_number', 'Du måste ange ett giltligt personnummer.');
                    }
                }

                try {
                    $focus_address_response = $focusapi->get_address($input['civic_number']);
                } catch (FocusApiException $e) {
                    $json_response = json_decode($e->getMessage());
                    if($json_response->status == 400 && $json_response->message == 'persnr: felaktigt personnummer'){
                        $validator->errors()->add('civic_number', 'Du måste ange ett giltligt personnummer.');
                    } else {
                        $validator->errors()->add('civic_number', 'Vi kunde tyvärr inte kontroller personnumret just nu, vänligen försök senare igen.');
                    }
                }

            }

        });

        // run validation errors
        if($validator->fails()){
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];
            return response()->json($response);
        }

        // Get state / Län based on zipcode
        try {
            // Format zip, remove whitespaces
            $zip_code = preg_replace("/\s+/", "", $focus_address_response['postnr'] ?? '');

            // Use Papiliteapi to get state based on zip
            $papilite = new PapiliteApi();
            $papilite_address = $papilite->get_address_from_zip($zip_code);

            // convert stupid state
            $focus_state = $focusapi->convert_state_to_focus($papilite_address['state'] ?? '');

            // if we have a state, set it
            if(isset($focus_state) && !empty($focus_state)){
                $input['state'] = $focus_state;
            } else {
                // On error, default state tå Okänt
                $input['state'] = 'Okänt';
            }

        } catch (PapiliteApiException $e) {
            report($e);
            // On error, default state tå Okänt
            $input['state'] = 'Okänt';
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
