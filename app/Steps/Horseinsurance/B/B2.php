<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Libraries\Papilite\PapiliteApi;
use App\Libraries\Papilite\PapiliteApiException;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class B2 extends StepAbstract
{
    public $name = 'hastforsakring-b-2';
    public $progressbar = 24;
    public $horse_usage;

    public function view(Request $request)
    {
        // Fetch session data
        $insurances = $this->get_data('hastforsakring-b-1.insurances') ?? [];
        $insurance_company_name = $this->get_data('hastforsakring-b-1.insurance_company_name') ?? 'försäkringsbolaget';

        // Fetch selected insurance
        if(count($insurances) == 1){
            $selected_insurance = 0;
        } else {
            $selected_insurance = $this->get_data($this->name.'.insurance') ?? null;
        }

        return view('steps.horseinsurance.b.b2', [
            'insurances' => $insurances,
            'insurance_company_name' => $insurance_company_name,
            'selected_insurance' => $selected_insurance
        ]);
    }

    public function validateStep(Request $request)
    {

        $focusapi = new FocusApi();

        $input = [
            'insurance' => $request->get('insurance')
        ];

        $rules = [
            'insurance'        => 'required|integer'
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

        // Set step data
        $steps_data = [];
        $insurances = $this->get_data('hastforsakring-b-1.insurances') ?? [];

        // Fill steps with data from insurance

        // Birthdate & Age
        if(isset($insurances[$input['insurance']]['dateOfBirth']) && !empty($insurances[$input['insurance']]['dateOfBirth'])){
            $steps_data['hastforsakring-b-4'] = [
                'fodelsedatum' => $insurances[$input['insurance']]['dateOfBirth'],
            ];

            try {
                $birthday = \Carbon\Carbon::parse($insurances[$input['insurance']]['dateOfBirth']);
                $steps_data['hastforsakring-b-4']['age'] = $birthday->diffInYears(now());
            } catch (\Exception $e){
                $birthday = null;
                $steps_data['hastforsakring-b-4']['age'] = 0;
            }

            if(
                isset($birthday) &&
                ($birthday->greaterThan(today()->subYears(3)) ||
                $birthday->lessThan(today()->subYears(15)))
            ){
                if(isset($steps_data['hastforsakring-b-4']['fodelsedatum'])){
                    unset($steps_data['hastforsakring-b-4']['fodelsedatum']);
                }
                if(isset($steps_data['hastforsakring-b-4']['age'])){
                    unset($steps_data['hastforsakring-b-4']['age']);
                }
            }

        } else {
            $steps_data['hastforsakring-b-4'] = [];
        }

        //pre($steps_data);

        // Gender
        if(isset($insurances[$input['insurance']]['animalGender']) && !empty($insurances[$input['insurance']]['animalGender'])){
            if($insurances[$input['insurance']]['animalGender'] == 'FEMALE'){
                $steps_data['hastforsakring-b-5']['gender'] = 'Sto';
            } elseif($insurances[$input['insurance']]['animalGender'] == 'MALE'){
                $steps_data['hastforsakring-b-5']['gender'] = 'Hingst';
            } elseif($insurances[$input['insurance']]['animalGender'] == 'CASTRATED_MALE'){
                $steps_data['hastforsakring-b-5']['gender'] = 'Valack';
            }
        } else {
            $steps_data['hastforsakring-b-5'] = [];
        }

        // Namn
        if(isset($insurances[$input['insurance']]['animalName']) && !empty($insurances[$input['insurance']]['animalName'])){
            $steps_data['hastforsakring-b-6'] = [
                'namn' => $insurances[$input['insurance']]['animalName'],
            ];
        } else {
            $steps_data['hastforsakring-b-6'] = [];
        }

        // Breed
        if(isset($insurances[$input['insurance']]['animalBreed']) && !empty($insurances[$input['insurance']]['animalBreed'])){
            $steps_data['hastforsakring-b-7']['breed'] = $insurances[$input['insurance']]['animalBreed'];
        } else {
            $steps_data['hastforsakring-b-7'] = [];
        }

        // Chipnumber
        if(isset($insurances[$input['insurance']]['chipNumber']) && !empty($insurances[$input['insurance']]['chipNumber'])){
            $steps_data['hastforsakring-b-9'] = [
                'chip_number' => $insurances[$input['insurance']]['chipNumber'],
            ];
        } else {
            $steps_data['hastforsakring-b-9'] = [];
        }

        // Civicnumber
        if(isset($insurances[$input['insurance']]['civic_number']) && !empty($insurances[$input['insurance']]['civic_number'])){
            $steps_data['hastforsakring-b-11'] = [
                'civic_number' => $insurances[$input['insurance']]['civic_number'],
            ];

            // get customer state
            try {
                $focus_address_response = $focusapi->get_address($insurances[$input['insurance']]['civic_number']);
            } catch (FocusApiException $e) {
                $focus_address_response = [];
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
                    $steps_data['hastforsakring-b-11']['state'] = $focus_state;
                } else {
                    // On error, default state tå Okänt
                    $steps_data['hastforsakring-b-11']['state'] = 'Okänt';
                }

            } catch (PapiliteApiException $e) {
                report($e);
                // On error, default state to Okänt
                $steps_data['hastforsakring-b-11']['state'] = 'Okänt';
            }

        } else {
            $steps_data['hastforsakring-b-11'] = [];
        }

        foreach($steps_data as $step => $step_data){
            $this->store_data($step_data, $step);
        }

        $next_step = 'hastforsakring-b-3';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }

}
