<?php namespace App\Steps\Farminsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;
use Mail;

class KontaktTack extends StepAbstract
{
    public $name = 'gardsforsakring-tack';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {

        // Clear session data
        $request->session()->forget('steps');

        // Clear session data
        return view('steps.farminsurance.kontakt-tack');
    }

    public function validateStep(Request $request)
    {
        // input
        $input = [];

        // rules
        $rules = [];

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

        return response()->json([
            'status' => 1,
            'next_step' => null
        ]);

    }

}
