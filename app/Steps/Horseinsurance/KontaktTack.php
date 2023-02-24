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

class KontaktTack extends StepAbstract
{
    public $name = 'kontakt-tack';
    public $progressbar = 100;
    public $skipable = false;

    public function view(Request $request)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $horse_name = $data['namn'] ?? '';
        $horse_usage_label = $data['horse_usage_label'] ?? 'Försäkring';

        // Clear session data
        $request->session()->forget('steps');

        return view('steps.horseinsurance.kontakt-tack', [
            'horse_name' => $horse_name,
            'horse_usage_label' => $horse_usage_label
        ]);

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

        return response()->json([
            'status' => 1,
            'next_step' => ''
        ]);

    }

}
