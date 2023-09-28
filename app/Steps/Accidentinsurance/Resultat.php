<?php

namespace App\Steps\Accidentinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Papilite\PapiliteApi;
use App\Steps\StepAbstract;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @todo Extract price method to an api controller.
 */
class Resultat extends StepAbstract
{
    public $name = 'olycksfallsforsakring-resultat';
    public $progressbar = 50;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Illuminate\View\View
     */
    public function view(Request $request)
    {
        return view('steps.accidentinsurance.resultat', [
            'ssn'        => $this->get_data('ssn'),
            'email'      => $this->get_data('email'),
            'phone'      => $this->get_data('phone'),
            'date'       => $this->get_data('date') ?? now()->toDateString(),
            'customer'   => $this->get_data('customer'),
            'uppsagning' => $request->get('uppsagning', null),
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
        $this->store_data($request->get('startdatum', null), 'date');
        $this->store_data($request->get('uppsagning', null), 'uppsagning');

        return response()->json([
            'status'    => 1,
            'next_step' => 'olycksfallsforsakring-sammanfattning',
        ]);
    }

    /**
     * Returns price based on user options.
     *
     * @return \Illuminate\Http\JsonResponse
     * @todo Extract code to classes to avoid abundant code.
     *
     */
    public function price()
    {
        $request = request();

        $startDate = !empty($request->get('startdatum'))
            ? Carbon::parse($request->get('startdatum'))
            : Carbon::now();

        $this->store_data($startDate, 'date');

        $customer = $this->get_data('customer');

        if (!empty($customer) && !empty($customer['kund'])) {
            try {
                $address = (new PapiliteApi)->get_address_from_zip(
                    $zip = preg_replace('~\D~', '', $customer['kund']['postnr'])
                );
            } catch (\Exception $exception) {
                $address = [];
            }

            $state = !empty($address['state']) ? $address['state'] : null;
            $state = (new FocusApi)->convert_state_to_focus($state);

            $this->store_data($state, 'state');
        }

        $fields = [
            383 => 1000000, // Medicinsk eller eko. Invaliditet
            384 => 50000, // Tandskada
            385 => 30000, // Kostnader pga olycksfallsskada
            386 => null, // Ärr
            387 => null, // Ersättning sjukhusvistelse
            388 => 25000, // Ersättning kläder, utrustning mm
            389 => 10000, // Kristerapi
            390 => 50000, // Dödsfall
            583 => $state ?? 'Okänt', // Län
        ];

        $payment_term = !empty($request->get('betalningstermin')) ? $request->get('betalningstermin') : 12;

        $price = (new FocusApi)->get_pris(34, $fields, $this->get_data('ssn'), $payment_term, null, $startDate->toDateString());

        if (isset($price['data']) && $price['data'] === false) {
            $price = [
                'utpris_per_termin' => 0,
                'utpris'            => 0,
                'netto'             => 0,
                'provision'         => 0,
            ];
        }

        if ($payment_term == 12) {
            $suffix = 'kr/år';
        } elseif ($payment_term == 3) {
            $suffix = 'kr/kvartal';
        } else {
            $suffix = 'kr/mån';
        }

        return [
            'price'     => number_format($price['utpris_per_termin'], 0, '.', ' '),
            'html'      => view('steps.accidentinsurance.resultat.pris', [
                'price'     => $price['utpris_per_termin'],
                'suffix'    => $suffix,
                'insurance' => 'Olyckfall',
                'ssn'       => $this->get_data('ssn')
            ])->render(),
        ];
    }
}
