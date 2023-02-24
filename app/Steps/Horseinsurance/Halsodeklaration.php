<?php namespace App\Steps\Horseinsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class Halsodeklaration extends StepAbstract
{
    public $name = 'halsodeklaration';
    public $progressbar = null;
    public $skipable = false;

    private $questions_villkor = [];
    private $document_type_id = null;
    private $document_type = 'health';

    public function set_villkor($document_type_id)
    {
        if(config('services.focus.live') == true){
            if($this->document_type_id == 4){
                $this->questions_villkor = [
                    46 => ['question' => 45, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    49 => ['question' => 48, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    51 => ['question' => 50, 'answers' => ['Nej', 'Nej*'], 'required' => true],
                    53 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    54 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    55 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true]
                ];
            } else {
                $this->questions_villkor = [];
            }
        } else {
            if($this->document_type_id == 4){
                $this->questions_villkor = [
                    46 => ['question' => 45, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    49 => ['question' => 48, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    51 => ['question' => 50, 'answers' => ['Nej', 'Nej*'], 'required' => true],
                    53 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    54 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true],
                    55 => ['question' => 52, 'answers' => ['Ja', 'Ja*'], 'required' => true]
                ];
            } else {
                $this->questions_villkor = [];
            }
        }
    }

    public function view(Request $request)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();


        // Fetch session data
        $answers = $this->get_data('halsodeklaration') ?? [];

        // Fetch session data
        $horse_usage = $data['horse_usage'];
        // Define villkor for live questions
        $this->document_type_id = (config('services.focus.live') == true) ? 4 : 4;
        $this->document_type = 'health';
        if($horse_usage == 2){
            $this->document_type = 'foal';
            $this->document_type_id = (config('services.focus.live') == true) ? 3 : 3;
        }
        $this->set_villkor($this->document_type_id);

        // Get questions from focus
        $focusapi = new FocusApi();
        $questions = $focusapi->get_questions($this->document_type_id);

        /*
        echo '<pre>'.print_r($questions,true).'</pre>';
        die('');
        */

        // Fetch session data
        $horse_name = $data['namn'] ?? '';
        $horse_usage_label = $data['horse_usage_label'] ?? 'Försäkring';

        // försäkringar
        $forsakringar = [];
        $forsakring_ver = $this->get_data('resultat.veterinarvardsforsakring');
        $forsakring_liv = $this->get_data('resultat.livforsakring');
        if(isset($forsakring_ver) && !empty($forsakring_ver)){
            $forsakringar[] = $forsakring_ver;
        }
        if(isset($forsakring_liv) && !empty($forsakring_liv)){
            $forsakringar[] = $forsakring_liv;
        }

        //get price
        $price = $this->get_price();

        return view('steps.horseinsurance.halsodeklaration', [
            'horse_name' => $horse_name,
            'answers'   => $answers,
            'questions' => $questions,
            'questions_villkor' => $this->questions_villkor,
            'price' => $price,
            'horse_usage_label' => $horse_usage_label,
            'forsakringar' => $forsakringar,
            'document_type' => $this->document_type
        ]);
    }

    public function validateStep(Request $request)
    {
        // Initiate focus api
        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        // Fetch session data
        $horse_usage = $data['horse_usage'];
        // Define villkor for live questions
        $this->document_type_id = (config('services.focus.live') == true) ? 4 : 4;
        $this->document_type = 'health';
        if($horse_usage == 2){
            $this->document_type = 'foal';
            $this->document_type_id = (config('services.focus.live') == true) ? 3 : 3;
        }
        $this->set_villkor($this->document_type_id);

        // Get questions from focus
        $questions = $focusapi->get_questions($this->document_type_id);

        $input = [
            'questions' => $request->get('questions'),
            'term' => $request->get('term', 0),
            'document_type' => $this->document_type_id
        ];

        $rules = [
            'term' => 'required|accepted',
            'document_type' => 'required'
        ];
        /*
        echo '<pre>'.print_r($questions, true).'</pre>';
        */

        // Appends questions to rules
        foreach($questions as $question){

            $required = false;

            // Skip validation
            if($question['obligatorisk'] == 1){
                $required = true;
            }

            if(
                isset($this->questions_villkor[$question['id']]) &&
                isset($this->questions_villkor[$question['id']]['question']) &&
                isset($input['questions'][$this->questions_villkor[$question['id']]['question']]) &&
                in_array($input['questions'][$this->questions_villkor[$question['id']]['question']], $this->questions_villkor[$question['id']]['answers'])
            ){
                $required = true;
            }

            if($required){
                switch($question['typ']){
                    case 'checkbox':
                        $rules['questions.'.$question['id']] = 'required|array|in:'.implode(',', $question['data']['options']);
                        break;
                    case 'radio':
                        $rules['questions.'.$question['id']] = 'required|in:'.implode(',', $question['data']['options']);
                        break;
                    case 'textarea':
                    case 'text':
                        $rules['questions.'.$question['id']] = 'required';
                        break;
                    case 'date':
                        $rules['questions.'.$question['id']] = 'required|date_format:Y-m-d';
                        break;
                }
            }
        }

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
            'next_step' => 'sammanfattning'
        ]);

    }

    public function get_price($defaults = null)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        if($data['horse_usage'] == 2){
            $focus_moments = collect($focusapi->get_moment(26));
        } else {
            // get moment
            $focus_moments = collect($focusapi->get_moment(22));
        }

        if(isset($data['veterinarvardsforsakring']) && !empty($data['veterinarvardsforsakring'])){
            $data['veterinarvardsforsakring_label'] = $focus_moments->where('id', $data['veterinarvardsforsakring'])->first()['namn'] ?? '';
        }
        if(isset($data['livforsakring']) && !empty($data['livforsakring'])){
            $data['livforsakring_label'] = $focus_moments->where('id', $data['livforsakring'])->first()['namn'] ?? '';
        }

        $moments = [];
        if(
            !empty($data['veterinarvardsforsakring']) &&
            array_key_exists('vet', $data['forsakring_enabled'])
        ){
            $moments[] =  $data['veterinarvardsforsakring'];
        }
        if(
            !empty($data['livforsakring']) &&
            $data['veterinarvardsforsakring'] != $data['livforsakring'] &&
            array_key_exists('liv', $data['forsakring_enabled'])
        ){
            $moments[] =  $data['livforsakring'];
        }

        $focus_fields = $focusapi->build_focus_fields($moments, $data);

        try {
            $termin = 1;
            // Foster och föl 12 månader
            if(isset($data['horse_usage']) && $data['horse_usage'] == 2){
                $termin = 12;
            }
            $focus_price_response = $focusapi->get_pris(implode(',',$moments), $focus_fields, $data['civic_number'], $termin);

            /*
            echo '<pre>'.print_r($focus_price_response,true).'</pre>';
            die();
            */

            $total_utpris = 0;
            $total_utpris_formated = 0;
            $total_total_utpris = 0;
            $total_total_formated_utpris = 0;
            $points = 0;

            // Monthly nad yearly
            if(isset($focus_price_response['utpris_per_termin'])){
                $total_utpris = $focus_price_response['utpris_per_termin'];
                $total_total_utpris = $focus_price_response['utpris'];
            } else {
                foreach($focus_price_response as $utpris){
                    if(isset($utpris['utpris_per_termin'])){
                        $total_utpris += $utpris['utpris_per_termin'];
                        $total_total_utpris += $utpris['utpris'];
                    }
                }
            }

            $total_utpris_formated = number_format($total_utpris,0,',',' ').' kr/'.(($termin == 1) ? 'mån' : 'år');
            $total_total_formated_utpris = number_format($total_total_utpris,0,',',' ');

            if($total_total_utpris > 5000){
                $points = 400;
            } else {
                $points = 200;
            }

        } catch (FocusApiException $e) {
            report($e);
            $total_utpris_formated = '-';
            $total_total_formated_utpris = '-';
            $total_utpris = 0;
            $total_total_utpris = 0;
            $points = 0;
        }

        $compare_insurance = $data['compare_insurance'] ?? null;

        return [
            'html' => view('steps.horseinsurance.resultat.pris', [
                'data' => $data,
                'utpris' => $total_utpris_formated,
                'utpris_formaterad' => $total_utpris_formated,
                'points' => $points,
                'disabled_checkboxes' => true,
                'compare_insurance' => $compare_insurance
            ])->render(),
            'utpris' => $total_utpris,
            'utpris_formaterad' => $total_utpris_formated,
            'points' => $points
        ];
    }

}
