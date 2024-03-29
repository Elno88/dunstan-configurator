<?php

namespace App\Steps\Accidentinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Mailchimp\MailchimpApi;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Tack extends StepAbstract
{
    public $name = 'olycksfallsforsakring-tack';
    public $progressbar = 100;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Illuminate\View
     */
    public function view(Request $request)
    {
        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();
        $customer = $this->get_data('customer');

        // Build google analytics ecommerce data
        try {
            $ecommerce_data = $this->build_ga_ecommerce_data();
        } catch (\Exception $e) {
            report($e);
            $ecommerce_data = [];
        }

        $ecommerce_data_send = !empty($ecommerce_data);

        // Update mailchimp tags
        if (isset($data['email']) && !empty($data['email'])) {
            // Mailchimp
            try {
                $mailchimpapi = new MailchimpApi;
                $mailchimpapi->subscribe_member($data['email'], []);
                $mailchimpapi->member_assign_tags($data['email'], ['webteckning-olycksfall']);
            } catch (\Exception $e) {
                report($e);
            }
        }

        // Clear session data
        $request->session()->forget('steps');
        $request->session()->forget('bankid');

        return view('steps.accidentinsurance.tack', [
            'customer'            => $customer,
            'ecommerce_data'      => $ecommerce_data,
            'ecommerce_data_send' => $ecommerce_data_send,
        ]);
    }

    /**
     * Validates the step.
     *
     * @param  \Illuminate\Http\Request  $request
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

        $product_id = array_keys($data['completed_products'] ?? []);

        if (isset($data['completed_products']) && !empty($data['completed_products'])) {
            foreach ($data['completed_products'] as $product) {
                $total_price += $product['total'];
            }
        } else {
            // end here already
            return $ecommerce;
        }

        $total_price = number_format($total_price, 2, '.', '');
        $ecommerce['event'] = 'purchase';
        $ecommerce['ecommerce']['purchase']['actionField'] = [
            'id' => $data['session_id'] ?? '',
            'affiliation' => 'Konfigurator',
            'revenue' => $total_price
        ];
        $ecommerce['ecommerce']['purchase']['products'] = [];

        // Set category based on manual or insurley
        $category = 'Olycksfallsförsäkring';

        $options = session()->get('steps.data.options', []);

        $ecommerce['ecommerce']['purchase']['products'][] = [
            'name'     => 'Olycksfallsförsäkring',
            'id'       => $product_id,
            'price'    => $total_price,
            'brand'    => 'Dunstan',
            'category' => $category,
            'quantity' => 1,
        ];

        return $ecommerce;
    }
}
