<?php namespace App\Steps\Horseinsurance\B;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use App\Steps\Horseinsurance\B\SkipSteps;

use Validator;

class B8 extends StepAbstract
{
    use SkipSteps;

    public $name = 'hastforsakring-b-8';
    public $progressbar = 65;
    public $skipable = false;

    public function view(Request $request)
    {

        // Fetch session data
        $folningdatum = $this->get_data($this->name.'.folningdatum');

        return view('steps.horseinsurance.a.a6', [
            'folningdatum' => $folningdatum
        ]);

    }

    public function validateStep(Request $request)
    {

        $input = [
            'folningdatum' => $request->get('folningdatum'),
        ];

        $rules = [
            'folningdatum'        => 'required|date:Y-m-d'
        ];

        $validator = Validator::make($input, $rules);

        // Calculate age from date
        try {
            $folningdatum = \Carbon\Carbon::parse($input['folningdatum']);
        } catch (\Exception $e){
            // Silent error (because validator will catch it anyways)
        }

        // Validate age
        $validator->after(function ($validator) use ($folningdatum) {

            // Fölning max 11 månader fram
            if($folningdatum->greaterThan(today()->addMonths(11))){
                $validator->errors()->add('age', 'Beräknat datum för fölning får max vara 11 månader fram. Vänligen kontakta servicecenter på <a href="tel:0101798400">010-179 84 00</a> så hjälper vi dig.');
            }
        });

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
