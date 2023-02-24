<?php namespace App\Steps\Horseinsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class Fosterofol extends StepAbstract
{
    public $name = 'foster-o-fol';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        //get price
        $price = $this->get_price();

        // Fetch session data
        $horse_name = $data['namn'] ?? '';
        $horse_usage_label = $data['horse_usage_label'] ?? 'Försäkring';

        return view('steps.horseinsurance.fosterofol', [
            'price' => $price,
            'horse_name' => $horse_name,
            'horse_usage_label' => $horse_usage_label
        ]);
    }

    public function validateStep(Request $request)
    {
        // Initiate focus api
        $input = [
            'term' => $request->get('term', 0),
        ];

        $rules = [
            'term' => 'required|accepted',
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

        return response()->json([
            'status' => 1,
            'next_step' => 'sammanfattning'
        ]);

    }

    public function get_price($defaults = null)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $focus_moments = collect($focusapi->get_moment(22));

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
