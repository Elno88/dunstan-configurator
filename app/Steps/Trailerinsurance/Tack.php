<?php

namespace App\Steps\Trailerinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

class Tack extends StepAbstract
{
    public $name = 'trailerforsakring-tack';
    public $progressbar = 100;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\View
     */
    public function view(Request $request)
    {
        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

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
                $mailchimpapi->member_assign_tags($data['email'], ['webteckning-trailer']);
            } catch (\Exception $e){
                report($e);
            }
        }

        // Clear session data
        $request->session()->forget('steps');
        $request->session()->forget('bankid');

        return view('steps.trailerinsurance.tack', [
            // 'ecommerce_data' => $ecommerce_data,
            // 'ecommerce_data_send' => $ecommerce_data_send,
        ]);
    }

    /**
     * Validates the step.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateStep(Request $request)
    {
        return response()->json([
            'status'    => 1,
            'next_step' => '',
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
        $category = 'TrailerFörsäkringNy';


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

        // TODO: this should be solved.

        $ecommerce['ecommerce']['purchase']['products'][] = [
            'name' => $data['livforsakring_label'],
            'id' => $data['livforsakring'],
            'price' => $price,
            'brand' => 'Dunstan',
            'category' => $category,
            'quantity' => 1,
        ];

        return $ecommerce;
    }
}
