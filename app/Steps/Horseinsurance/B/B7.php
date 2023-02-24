<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B7 extends StepAbstract
{

    use SkipSteps;

    public $name = 'hastforsakring-b-7';
    public $progressbar = 59;

    public function view(Request $request)
    {
        $focusapi = new FocusApi();
        $focus_response = $focusapi->get_moment(22);

        $breeds = [];
        foreach($focus_response as $moments){
            foreach($moments['falt'] as $field){
                if($field['namn'] == 'Ras'){
                    $breeds = $field['alternativ'];
                    break 2;
                }
            }
        }

        // Fetch session data
        $selected_breed = $this->get_data($this->name.'.breed');

        $search_array_breeds = [
            'Svenskt Varmblod (SWB)',
            'Islandshäst',
            'Varmblodstravare',
            'Korsningsponny',
            'New ForestPonny'
        ];

        $breed_priority = [];
        foreach($search_array_breeds as $search){
            $key = array_search($search, $breeds); // $key = 2;
            if($key !== false){
                $breed_priority[] = $key;
            }
        }

        // Fetch horse name
        $horse_name = $this->get_data('hastforsakring-b-6.namn');

        return view('steps.horseinsurance.a.a5', [
            'breeds' => $breeds,
            'selected_breed' => $selected_breed,
            'breed_priority' => $breed_priority,
            'horse_name' => $horse_name
        ]);
    }

    public function validateStep(Request $request)
    {

        $input = [
            'breed' => $request->get('breed')
        ];

        $rules = [
            'breed'        => 'required'
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

        $next_step = $this->skip_to_next_available_step($this->name);

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
