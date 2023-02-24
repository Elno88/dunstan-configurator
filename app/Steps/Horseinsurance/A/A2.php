<?php namespace App\Steps\Horseinsurance\A;

use App\Http\Controllers\Controller;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class A2 extends StepAbstract
{

    public $name = 'hastforsakring-a-2';
    public $progressbar = 25;

    public function view(Request $request)
    {

        // Get data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');

        // Fetch session data
        $birthdate = $this->get_data($this->name.'.fodelsedatum');

        return view('steps.horseinsurance.a.a2', [
            'birthdate' => $birthdate,
            'horse_usage' => $horse_usage
        ]);
    }

    public function validateStep(Request $request)
    {

        // Get data
        $horse_usage = $this->get_data('hastforsakring-a-1.horse_usage');

        $input = [
            'fodelsedatum' => $request->get('fodelsedatum'),
            'age'          => ''
        ];

        $rules = [
            'fodelsedatum'        => 'required|date:Y-m-d'
        ];

        $validator = Validator::make($input, $rules);


        // Calculate age from date
        try {
            $birthday = \Carbon\Carbon::parse($input['fodelsedatum']);
            $input['age'] = $birthday->diffInYears(today());
        } catch (\Exception $e){
                // Silent error (because validator will catch it anyways)
        }

        // Validate age
        $validator->after(function ($validator) use ($input, $birthday, $horse_usage) {

            // om foster och föl, kolla mellan 3 och 18 år
            if(isset($horse_usage) && $horse_usage == 2){
                if(
                    $birthday->greaterThan(today()->subYears(3)) ||
                    $birthday->lessThan(today()->subYears(18))
                ){
                    $validator->errors()->add('age', 'Stoet måste vara mellan 3 till 18 år år gammal. Vänligen kontakta servicecenter på <a href="tel:0101798400">010-179 84 00</a> så hjälper vi dig.');
                }
            } else {
                if($birthday->lessThan(today()->subYears(15))){
                    $validator->errors()->add('age', 'Din häst verkar vara över 15 år gammal. Vänligen kontakta servicecenter på <a href="tel:0101798400">010-179 84 00</a> så hjälper vi dig.');
                }
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

        $next_step = 'hastforsakring-a-3';

        // Change step if foster & föl
        if(isset($horse_usage) && $horse_usage == 2){
            $next_step = 'hastforsakring-a-4';
        }

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);

    }
}
