<?php

namespace App\Steps\Trailerinsurance;

use App\Libraries\Focus\FocusApi;
use App\Libraries\Papilite\PapiliteApi;
use App\Steps\StepAbstract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @todo Extract price method to an api controller.
 */
class Resultat extends StepAbstract
{
    public $name = 'trailerforsakring-resultat';
    public $progressbar = 64;
    public $skipable = false;

    /**
     * Shows the step/page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function view(Request $request)
    {
        $options = $this->get_data('options');
        $vehicle = $this->get_data('vehicle');

        return view('steps.trailerinsurance.resultat', [
            'safety'  => $options['safety'] ?? 'Normal',
            'form'    => $options['form'] ?? 'Grund',
            'benefit' => $options['benefit'] ?? null,
            'date'    => $options['date'] ?? date('Y-m-d'),
            'vehicle' => $vehicle ?? null,
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
        $this->store_data([
            'safety'      => $request->get('safety', 'Normal'),
            'form'        => $request->get('form', 'Grund'),
            'date'        => $request->get('startdatum', null),
            'termination' => $request->get('uppsagning', null),
        ], 'options');

        return response()->json([
            'status'    => 1,
            'next_step' => 'trailerforsakring-sammanfattning',
        ]);
    }

    /**
     * Returns price based on user options.
     *
     * @todo Extract code to classes to avoid abundant code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function price()
    {
        $request = request();

        $startDate = !empty($request->get('startdatum'))
            ? Carbon::parse($request->get('startdatum'))
            : Carbon::now();

        $payment = !empty($request->get('payment'))
            ? (int) $request->get('payment')
            : 12;

        $ssn = $this->get_data('ssn');
        $customer = $this->get_data('customer');
        $vehicle = $this->get_data('vehicle');

        if (!empty($customer) && !empty($customer['kund'])) {
            try {
                $address = (new PapiliteApi)->get_address_from_zip(
                    $zip = preg_replace('~\D~', '', $customer['kund']['postnr'])
                );
            } catch (\Exception $exception) {
                return response()->json([
                    'status' => 0,
                    'error'  => $exception->getMessage(),
                ]);
            }

            $state = !empty($address['state']) ? $address['state'] : null;
            $state = (new FocusApi)->convert_state_to_focus($state);
        }

        $this->store_data([
            'safety'   => $request->get('safety', 'Normal'),
            'form'     => $request->get('form', 'Grund'),
            'benefit'  => $this->getBenefitLevel(),
            'date'     => $request->get('startdatum', null),
            'state'    => $state ?? 'Okänt',
        ], 'options');

        if (config('services.focus.live')) {
            $fields = [
                630 => $vehicle['regnr'], // Registreringsnummer
                631 => $vehicle['make'], // Fabrikat
                632 => $vehicle['model'], // Fordonsslag
                633 => $vehicle['year'] ?? 1900, // Årsmodell
                634 => $vehicle['total_weight'] ?? 0, // Totalvikt
                635 => $vehicle['service_weight'] ?? 0, // Tjänstevikt
                658 => 'För transport av djur', // Kaross
                639 => $request->get('safety', 'Normal'), // Säkerhetsanordningar
                638 => $this->getBenefitLevel(), // Förmånsnivå
                640 => Str::title($request->get('form', 'grund')), // Försäkringsform
                641 => null, // Självrisk
                643 => null, // Ägare
                642 => $state ?? 'Okänt', // Län
            ];
        } else {
            $fields = [
                652 => $vehicle['regnr'], // Registreringsnummer
                653 => $vehicle['make'], // Fabrikat
                654 => $vehicle['model'], // Fordonsslag
                655 => $vehicle['year'] ?? 1900, // Årsmodell
                656 => $vehicle['total_weight'] ?? 0, // Totalvikt
                657 => $vehicle['service_weight'] ?? 0, // Tjänstevikt
                658 => 'För transport av djur', // Kaross
                660 => $request->get('safety', 'Normal'), // Säkerhetsanordningar
                661 => $this->getBenefitLevel(), // Förmånsnivå
                659 => Str::title($request->get('form', 'grund')), // Försäkringsform
                663 => null, // Självrisk
                671 => null, // Ägare
                670 => $state ?? 'Okänt', // Län
            ];
        }

        try {
            $data = (new FocusApi)->get_pris(47, $fields, $ssn, $payment, null, $startDate->addMonth(1)->toDateString());
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        dd($data);

        return [
            'price' => number_format($data['utpris'], 0, '.', ' '),
            'html'  => view('steps.trailerinsurance.resultat.pris', [
                'price'     => $data['utpris'],
                'insurance' => 'Hästrailer',
                'safety'    => $request->get('safety', 'Normal'),
                'form'      => Str::title($request->get('form', 'grund')),
                'benefit'   => $this->getBenefitLevel(),
            ])->render(),
        ];
    }

    /**
     * Returns the textual string representing the benefit level.
     *
     * @todo Extract code to classes to avoid abundant code.
     *
     * @return string
     */
    protected function getBenefitLevel()
    {
        $customer = $this->get_data('customer');

        if (empty($customer) || empty($customer['forsakringar'])) {
            return 'Nej';
        }

        $insurances = collect($customer['forsakringar']);

        $insurances = $insurances->filter(function ($insurance) {
            return in_array($insurance['produktId'], [22, 23, 26]);
        });

        if ($insurances->isEmpty()) {
            return 'Nej';
        }

        if (($insurances->contains(26) && ($insurances->contains(22)) || $insurances->contains(23))) {
            return 'Gårds- och hästförsäkring i Dunstan';
        } elseif ($insurances->contains(26)) {
            return 'Gårdsförsäkring i Dunstan';
        } elseif ($insurances->contains(23)) {
            return 'Hästförsäkring i Dunstan';
        } elseif ($insurances->contains(22)) {
            return 'Hästförsäkring i Dunstan';
        }

        return 'Nej';
    }
}
