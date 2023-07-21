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
                $mailchimpapi->member_assign_tags($data['email'], ['Webteckning-trailer']);
            } catch (\Exception $e){
                report($e);
            }
        }

        // Clear session data
        $request->session()->forget('steps');
        $request->session()->forget('bankid');

        return view('steps.trailerinsurance.tack', [
            'ecommerce_data' => $ecommerce_data,
            'ecommerce_data_send' => $ecommerce_data_send,
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
}
