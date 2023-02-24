<?php namespace App\Steps\Farminsurance\B;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class B1 extends StepAbstract
{
    public $name = 'gardsforsakring-b-1';
    public $progressbar = 50;
    public $skipable = true;

    public function view(Request $request)
    {

        // Fetch session data
        if(config('services.google_maps.live')){
            $google_maps_secret = config('services.google_maps.secret_live');
        } else {
            $google_maps_secret = config('services.google_maps.secret_test');
        }

        return view('steps.farminsurance.b.b1', [
            'google_maps_secret' => $google_maps_secret
        ]);
    }

    public function validateStep(Request $request)
    {
        $input = [
            'street' => $request->get('street'),
            'zip' => $request->get('zip'),
            'city' => $request->get('city'),
            'skip'  => $request->get('skip', 0)
        ];

        $rules = [
            //'street' => 'required',
            //'zip' => 'required',
            'city' => 'required'
        ];

        // Skip
        if($input['skip'] == 1 && $this->skipable){

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

        // Store data
        $this->store_data($input);

        $next_step = 'gardsforsakring-b-2';

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }

}
