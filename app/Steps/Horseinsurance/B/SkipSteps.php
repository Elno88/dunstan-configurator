<?php namespace App\Steps\Horseinsurance\B;

trait SkipSteps {

    public function skip_to_next_available_step($step)
    {

        // Available steps in order
        $available_steps = [
            'hastforsakring-b-1', //0 insurley
            'hastforsakring-b-2', //1 insurances
            'hastforsakring-b-3', //2 horse_usage
            'hastforsakring-b-4', //3 födelsedata
            'hastforsakring-b-5', //4 gender
            'hastforsakring-b-6', //5 namn
            'hastforsakring-b-ff-forsakring', // forsakring
            'hastforsakring-b-7', //6 breed
            'hastforsakring-b-ff-betackning', // betackning
            'hastforsakring-b-8', //7 foster o föl
            'hastforsakring-b-9', //8 chip_number
            'hastforsakring-b-10', //9 born
            'hastforsakring-b-11', //10 civic_number
            'hastforsakring-b-12', //11 email
            'resultat', //12 resultat
            'halsodeklaration', //13 hälsodeklaration
            'sammanfattning' //14 sammanfattning
        ];

        // Step data to check
        $step_data = [
            'hastforsakring-b-4' => [
                'fodelsedatum',
                'age'
            ],
            'hastforsakring-b-5' => [
                'gender'
            ],
            'hastforsakring-b-6' => [
                'namn'
            ],
            /*
            'hastforsakring-b-7' => [
                'breed'
            ],
            */
            'hastforsakring-b-9' => [
                'chip_number'
            ],
            'hastforsakring-b-11' => [
                'civic_number'
            ],
        ];

        // Default steps to skip
        $steps_to_skip = [
            'hastforsakring-b-8',
            'hastforsakring-b-ff-forsakring',
            'hastforsakring-b-ff-betackning',
        ];

        $horse_usage = $this->get_data('hastforsakring-b-3.horse_usage') ?? null;
        // Steps to skip if horse_usage is foster and föl,2
        if(isset($horse_usage) && $horse_usage == 2) {
            $steps_to_skip = [
                'hastforsakring-b-5',
                'hastforsakring-b-10'
            ];
        } else {
            // unset breed step
            unset($available_steps[8]);
            $available_steps = array_values($available_steps);
        }

        // Get current step
        $next_step_test = array_search($step, $available_steps);

        // Check if we have a match
        if($next_step_test !== false){

            // Get next step to check
            $next_step_index = $next_step_test+1;

            // Loop steps as long as they exists
            while(isset($available_steps[$next_step_index])){

                // skip step if it's in steps_to_skip
                if(in_array($available_steps[$next_step_index], $steps_to_skip)){
                    $next_step_index++;
                    continue;
                }

                // Check if stepdata exists
                if(isset($step_data[$available_steps[$next_step_index]])){

                    // Set default to skip step
                    $can_skip = true;
                    // Fetch session data for the step
                    $check_data = $this->get_data($available_steps[$next_step_index]) ?? null;

                    // Loop stepdata
                    foreach($step_data[$available_steps[$next_step_index]] as $value){
                        // If stepdata is missing, step can not skip
                        if(!isset($check_data[$value]) || empty($check_data[$value])){
                            $can_skip = false;
                            break;
                        }
                    }

                    // If can't skip, return step
                    if(!$can_skip){
                        return $available_steps[$next_step_index];
                    }
                } else {
                    // If not stepdata, return step
                    return $available_steps[$next_step_index];
                }

                // increase step index by 1
                $next_step_index++;
            }
        }

        // If we can't find a step return null
        return null;
    }

}
