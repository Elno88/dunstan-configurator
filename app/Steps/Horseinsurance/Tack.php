<?php namespace App\Steps\Horseinsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class Tack extends StepAbstract
{
    public $name = 'tack';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        // Fetch session data
        $horse_name = $data['namn'] ?? '';

        // Build google analytics ecommerce data
        try {
            $ecommerce_data = $this->build_ga_ecommerce_data();
        } catch (\Exception $e) {
            report($e);
            $ecommerce_data = [];
        }
        $ecommerce_data_send = !empty($ecommerce_data);

        // Update mailchimp tags
        if(isset($data['email']) && !empty($data['email'])){
            // Mailchimp
            try {
                $mailchimpapi = new MailchimpApi;
                $mailchimpapi->subscribe_member($data['email'], []);
                $mailchimpapi->member_assign_tags($data['email'], ['Webteckning']);
            } catch (\Exception $e){
                report($e);
            }
        }

        // Clear session data
        $request->session()->forget('steps');
        $request->session()->forget('bankid');

        return view('steps.horseinsurance.tack', [
            'horse_name'=> $horse_name,
            'ecommerce_data' => $ecommerce_data,
            'ecommerce_data_send' => $ecommerce_data_send,
        ]);

    }

    // Empty validation because not used
    public function validateStep(Request $request)
    {
        return response()->json([
            'status' => 1,
            'next_step' => ''
        ]);
    }

    public function build_ga_ecommerce_data()
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $ecommerce = [];

        $total_price = 0;
        if(isset($data['completed_products']) && !empty($data['completed_products'])){
            foreach($data['completed_products'] as $product){
                $total_price += $product['total'];
            }
        } else {
            // end here already
            return $ecommerce;
        }

        $total_price = number_format($total_price,2,'.','');
        $ecommerce['event'] = 'purchase';
        $ecommerce['ecommerce']['purchase']['actionField'] = [
            'id' => $data['session_id'] ?? '',
            'affiliation' => 'Konfigurator',
            'revenue' => $total_price
        ];
        $ecommerce['ecommerce']['purchase']['products'] = [];

        // Set category based on manual or insurley
        $category = 'FörsäkringNy';
        if(isset($data['step_insurance']) && $data['step_insurance'] == 'hastforsakring-b-1'){
            $category = 'FörsäkringJamför';
        }

        // Veterinärförsäkring
        if(
            isset($data['veterinarvardsforsakring']) &&
            !empty($data['veterinarvardsforsakring']) &&
            isset($data['completed_products'][$data['veterinarvardsforsakring']]['total'])
        ){
            $price = 0;
            if(isset($data['completed_products'][$data['veterinarvardsforsakring']])){
                $price = number_format($data['completed_products'][$data['veterinarvardsforsakring']]['total'],2,'.','');
            }
            $ecommerce['ecommerce']['purchase']['products'][] = [
                'name' => $data['veterinarvardsforsakring_label'],
                'id' => $data['veterinarvardsforsakring'],
                'price' => $price,
                'brand' => 'Dunstan',
                'category' => $category,
                'quantity' => 1,
            ];
        }

        // Livförsäkring, om den inte är samma som veterinärförsäkring
        if(
            isset($data['livforsakring']) &&
            !empty($data['livforsakring']) &&
            $data['livforsakring'] != $data['veterinarvardsforsakring'] &&
            isset($data['completed_products'][$data['livforsakring']]['total'])
        ){
            $price = 0;
            if(isset($data['completed_products'][$data['livforsakring']])){
                $price = number_format($data['completed_products'][$data['livforsakring']]['total'],2,'.','');
            }
            $ecommerce['ecommerce']['purchase']['products'][] = [
                'name' => $data['livforsakring_label'],
                'id' => $data['livforsakring'],
                'price' => $price,
                'brand' => 'Dunstan',
                'category' => $category,
                'quantity' => 1,
            ];
        }

        return $ecommerce;
    }

}
