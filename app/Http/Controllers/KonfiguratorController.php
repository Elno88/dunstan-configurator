<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KonfiguratorController extends Controller
{

    private $steps = [];

    // Constructor to initate the steps from config
    public function __construct()
    {
        $this->steps = $this->initiateSteps();
    }

    // Index
    public function index(Request $request)
    {
        return view('pages.index');
    }

    // Get step
    public function step(Request $request, $step, $function = null)
    {
        // Check current step with path (maybe invalidate step)
        $step_obj = $this->getSteps($step);
        if(is_null($step_obj)){
            app()->abort(404);
        }

        // Ajax calls from steps, like bankid, reload elements
        if(!empty($function)){
            return $step_obj->{$function}();
        } else {

            // Create a session id if it doesn't exist
            $step_session_id = $request->session()->get('steps.session_id', null);
            if(empty($step_session_id)){
                $request->session()->put('steps.session_id', (string) Str::orderedUuid());
            }

            // Should probably check current step (previous step) matches session current step
            $request->session()->put('steps.current_step', $step_obj->name);

            $response_step = [
                'name' => $step,
                'html' => $step_obj->view($request)->render(),
                'progressbar' => $step_obj->progressbar
            ];

            return $response_step;
        }

    }

    // Validate a step, uses validation logic from each step
    public function validateStep(Request $request, $step)
    {
        $get_step = $this->getSteps($step);

        if(is_null($get_step)){
            return null;
        }

        // Get current step from session
        // If current step doesn't match path, maybe invalidate the validation
        // Validate current step

        return $get_step->validateStep($request);
    }

    // Inititate all steps
    private function initiateSteps()
    {
        $config_steps = config('steps.steps');
        $new_steps = [];
        foreach($config_steps as $step){
            $step_obj = app()->make($step);
            $new_steps[$step_obj->name] = $step_obj;
        }

        return $new_steps;
    }

    // Get all steps or one specific step
    private function getSteps($step = null)
    {
        if(!is_null($step)){
            if(isset($this->steps[$step])){
                return $this->steps[$step];
            } else {
                return null;
            }
        }
        return $this->steps;
    }

    private function setStep($step)
    {

    }

}
