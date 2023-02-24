<?php namespace App\Steps;

use App\Http\Controllers\Controller;

abstract class StepAbstract extends Controller implements StepInterface {
    public $name = 'step';
    public $progressbar = 0;
    public $skipable = false;

    public function store_data($data, $step = null){
        if(is_null($step)){
            $step = $this->name;
        }
        request()->session()->put('steps.data.'.$step, $data);
    }

    public function get_data($index = null)
    {
        $session_data = 'steps';
        if(!is_null($index)){
            $session_data = 'steps.data.'.$index;
        }
        return request()->session()->get($session_data);
    }
}
