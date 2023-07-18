<?php

namespace App\Steps\Horseinsurance;

use App\Http\Controllers\Controller;
use App\Libraries\Focus\FocusApi;
use App\Libraries\Focus\FocusApiException;
use App\Steps\StepInterface;
use App\Steps\StepAbstract;
use Illuminate\Http\Request;

use Validator;

class Resultat extends StepAbstract
{
    public $name = 'resultat';
    public $progressbar = null;
    public $skipable = false;

    public function view(Request $request)
    {
        // Fetch session data
        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $horse_name = $data['namn'] ?? '';
        $startdatum = $data['startdatum'] ?? now()->format('Y-m-d');
        $horse_usage = $data['horse_usage'] ?? '0';
        $horse_usage_label = $data['horse_usage_label'] ?? 'Försäkring';
        $compare_insurance = $data['compare_insurance'] ?? null;

        $insurances = $this->get_insurances();
        $resultat_template = $this->reload_template();

        // försäkringar
        $forsakringar = [];
        $forsakring_ver = $this->get_data('resultat.veterinarvardsforsakring');
        $forsakring_liv = $this->get_data('resultat.livforsakring');
        if (isset($forsakring_ver) && !empty($forsakring_ver)) {
            $forsakringar[] = $forsakring_ver;
        }
        if (isset($forsakring_liv) && !empty($forsakring_liv)) {
            $forsakringar[] = $forsakring_liv;
        }

        return view('steps.horseinsurance.resultat', [
            'resultat_template' => $resultat_template['html'],
            'price' => $resultat_template['price'],
            'defaults' => $resultat_template['defaults'],
            'available' => $resultat_template['available'],
            'insurances' => $insurances,
            'horse_name' => $horse_name,
            'startdatum' => $startdatum,
            'horse_usage' => $horse_usage,
            'horse_usage_label' => $horse_usage_label,
            'forsakringar' => $forsakringar,
            'compare_insurance' => $compare_insurance
        ]);
    }

    public function reload_template()
    {

        $focusapi = new FocusApi();
        $data = $focusapi->get_shared_focus_data();

        $input = [
            'veterinarvardsforsakring'  => request()->get('veterinarvardsforsakring'),
            'veterinarvardsbelopp'      => request()->get('veterinarvardsbelopp'),
            'livforsakring'             => request()->get('livforsakring'),
            'livvarde'                  => request()->get('livvarde'),
            'sjalvrisk'                 => request()->get('sjalvrisk'),
            'startdatum'                => request()->get('startdatum'),
            'safestart'                 => request()->get('safestart'),
            'uppsagning'                => request()->get('uppsagning'),
            'swbmedlem'                 => request()->get('swbmedlem'),
            'stable'                    => request()->get('stable'),
        ];

        // Fetch horse_usage to determine insurances
        $insurances = $this->get_insurances();
        $available = [];
        $defaults = [];

        // Set available data depending on horse_usage
        $horse_usage = $data['horse_usage'];

        switch ($horse_usage) {
                // Föl och unghäst
                // Ridhäst
            case 6:
            case 7:
            case 8:
            case 1:
                // available
                $available = [
                    'veterinarvardsforsakring' => [8, 6, 4],
                    'veterinarvardsbelopp' => [50000, 100000, 150000],
                    'livforsakring' => [
                        'all' => [14, 17, 13, 12],
                        8 => [14, 17, 13, 12],
                        6 => [14, 17, 13, 12],
                        4 => [14, 17, 13, 12]
                    ],
                    'livvarde' => [15000, 250000],
                    'livvarde_increment' => 5000,
                ];

                break;
                // Foster & Föl
                /* old foster och föl
            case 2:
                $available = [
                    'veterinarvardsforsakring' => [20],
                    'veterinarvardsbelopp' => [100000],
                    'livforsakring' => [
                        'all' => [20],
                        20 => [20]
                    ],
                    'livvarde' => [1000, 75000],
                    'livvarde_increment' => 1000,
                ];
                break;
            */
                // Nya foster 0 föl
            case 2:
                $available = [
                    'veterinarvardsforsakring' => [38],
                    'veterinarvardsbelopp' => [100000],
                    'livforsakring' => [
                        'all' => [38],
                        38 => [38]
                    ],
                    'livvarde' => [1000, 75000],
                    'livvarde_increment' => 1000,
                    'safestart' => 0
                ];
                break;
                // Breeding
            case 3:
                $available = [
                    'veterinarvardsforsakring' => [7],
                    'veterinarvardsbelopp' => [50000, 100000, 150000],
                    'livforsakring' => [
                        'all' => [16],
                        7 => [16]
                    ],
                    'livvarde' => [15000, 250000],
                    'livvarde_increment' => 5000,
                ];
                break;
                // Galopp & Trav
            case 4:
            case 5:
                $available = [
                    'veterinarvardsforsakring' => [8, 6],
                    'veterinarvardsbelopp' => [50000, 100000, 150000],
                    'livforsakring' => [
                        'all' => [14, 17, 13],
                        8 => [14, 17, 13],
                        6 => [14, 17, 13],
                    ],
                    'livvarde' => [15000, 250000],
                    'livvarde_increment' => 5000,
                ];
                break;
        }

        // default
        $defaults = [
            'veterinarvardsforsakring' => $input['veterinarvardsforsakring'] ?? last($available['veterinarvardsforsakring']) ?? null,
            'livvarde' => 15000,
            'sjalvrisk_options' => [25, 50],
            'sjalvrisk' => 25,
            'safestart' => 0
        ];
        $defaults['veterinarvardsbelopp'] = $available['veterinarvardsbelopp'][0] ?? null;
        if (isset($available['livforsakring'][$defaults['veterinarvardsforsakring']])) {
            $defaults['livforsakring'] = last($available['livforsakring'][$defaults['veterinarvardsforsakring']]);
        } else {
            $defaults['livforsakring'] = null;
        }
        if (empty($defaults['livforsakring'])) {
            $defaults['livforsakring'] = null;
        }

        // Foster o föl
        /* old foster o föl
        if($defaults['veterinarvardsforsakring'] == 20){
            $defaults['sjalvrisk_options'] = [25];
            $defaults['livvarde'] = 1000;
        }*/
        // Nya Foster o föl
        if ($defaults['veterinarvardsforsakring'] == 38) {
            $defaults['sjalvrisk_options'] = [25];
            $defaults['livvarde'] = 1000;
        }

        // If we have compare from insurley set default veterinarvardsbelopp
        $compare_insurance = $data['compare_insurance'] ?? null;
        if (isset($compare_insurance['veterinaryCareAmount']) && !empty($compare_insurance['veterinaryCareAmount'])) {
            $veterinary_care_amount = (int) $compare_insurance['veterinaryCareAmount'];
            if (isset($available['veterinarvardsbelopp']) && !empty($available['veterinarvardsbelopp'])) {
                // Loop all
                foreach ($available['veterinarvardsbelopp'] as $key => $belopp) {
                    // Under
                    if (!isset($available['veterinarvardsbelopp'][$key - 1]) && $veterinary_care_amount <= $belopp) {
                        $defaults['veterinarvardsbelopp'] = $belopp;
                        break;
                    }
                    // Above
                    if (!isset($available['veterinarvardsbelopp'][$key + 1]) && $veterinary_care_amount >= $belopp) {
                        $defaults['veterinarvardsbelopp'] = $belopp;
                        break;
                    }
                    // Between
                    if ($veterinary_care_amount >= $belopp - 25000 && $veterinary_care_amount < $belopp + 25000) {
                        $defaults['veterinarvardsbelopp'] = $belopp;
                        break;
                    }
                }
            }
        }

        // If we have compare from insurley set default livvarde
        /*
        if(isset($compare_insurance['animalPurchasePrice']) && !empty($compare_insurance['animalPurchasePrice'])){
            $animal_purchase_price = (int) $compare_insurance['animalPurchasePrice'];
            if(
                isset($available['livvarde']) &&
                is_array($available['livvarde']) &&
                count($available['livvarde']) == 2 &&
                $animal_purchase_price >= $available['livvarde'][0] &&
                $animal_purchase_price <= $available['livvarde'][1]
            ){
                $defaults['livvarde'] = $animal_purchase_price;
            }
        }
        */

        // Keep selected values if they still exists
        if (isset($input['veterinarvardsbelopp']) && !empty($input['veterinarvardsbelopp'])) {
            if (in_array($input['veterinarvardsbelopp'], $available['veterinarvardsbelopp'])) {
                $defaults['veterinarvardsbelopp'] = $input['veterinarvardsbelopp'];
            }
        }

        if (isset($input['livforsakring']) && !empty($input['livforsakring'])) {
            if (isset($available['livforsakring'][$defaults['veterinarvardsforsakring']]) && in_array($input['livforsakring'], $available['livforsakring'][$defaults['veterinarvardsforsakring']])) {
                $defaults['livforsakring'] = $input['livforsakring'];
            }
        }

        if (isset($input['livvarde']) && !empty($input['livvarde'])) {
            if (!empty($available['livvarde'])) {
                $defaults['livvarde'] = $input['livvarde'];
            }
        }

        if (isset($input['sjalvrisk']) && !empty($input['sjalvrisk'])) {
            if (isset($defaults['sjalvrisk_options']) && in_array($input['sjalvrisk'], $defaults['sjalvrisk_options'])) {
                $defaults['sjalvrisk'] = $input['sjalvrisk'];
            }
        }

        if (isset($input['safestart']) && !empty($input['safestart'])) {
            if (isset($available['safestart']) && !empty($available['safestart'])) {
                $defaults['safestart'] = $input['safestart'];
            }
        }

        if (isset($input['startdatum']) && !empty($input['startdatum'])) {
            $defaults['startdatum'] = $input['startdatum'];
        }

        if (isset($input['swbmedlem']) && !empty($input['swbmedlem'])) {
            $defaults['swbmedlem'] = $input['swbmedlem'];
        }

        if (isset($input['stable']) && !empty($input['stable'])) {
            $defaults['stable'] = $input['stable'];
        }

        // if Katastrof, remove självrisk and belopp
        if ($defaults['veterinarvardsforsakring'] == 14) {
            $defaults['sjalvrisk'] = null;
            $defaults['sjalvrisk_options'] = null;
            $defaults['veterinarvardsbelopp'] = null;
        }

        $this->store_data($defaults);

        $get_price = $this->get_price($defaults);

        return [
            'html' => view('steps.horseinsurance.resultat.template', [
                'insurances' => $insurances,
                'available' => $available,
                'defaults' => $defaults,
                'price' => $get_price
            ])->render(),

            'defaults' => $defaults,
            'available' => $available,
            'price' => $get_price
        ];
    }

    public function validateStep(Request $request)
    {
        $focusapi = new FocusApi();

        $data = $focusapi->get_shared_focus_data();

        $horse_usage = $data['horse_usage'] ?? null;

        // input
        $input = [
            'veterinarvardsforsakring'  => $request->get('veterinarvardsforsakring'),
            'veterinarvardsbelopp'      => $request->get('veterinarvardsbelopp'),
            'livforsakring'             => $request->get('livforsakring'),
            'livvarde'                  => $request->get('livvarde'),
            'sjalvrisk'                 => $request->get('sjalvrisk'),
            'startdatum'                => $request->get('startdatum'),
            'forsakring_enabled'        => $request->get('forsakring_enabled'),
            'safestart'                 => $request->get('safestart', 0),
            'uppsagning'                => $request->get('uppsagning'),
            'swbmedlem'                 => $request->get('swbmedlem'),
            'stable'                    => $request->get('stable'),
        ];

        // rules
        $rules = [
            'forsakring_enabled' => 'required|array',
        ];

        $validation_messages = [
            'startdatum.required' => 'Du måste välja ett giltligt startdatum.',
            'startdatum.date' => 'Du måste välja ett giltligt startdatum.',
            'startdatum.after_or_equal' => 'Startdatumet får inte vara tidigare än idag.',
        ];

        // foster o föl 40 days, other 90 days
        if (isset($horse_usage) && $horse_usage == 2) {
            $rules['startdatum'] = 'required|date:Y-m-d|after_or_equal:' . today()->format('Y-m-d') . '|before:' . today()->addDays(40)->format('Y-m-d');
            $validation_messages['startdatum.before'] = 'Startdatumet får inte vara längre än 40 dagar fram.';
        } else {
            $rules['startdatum'] = 'required|date:Y-m-d|after_or_equal:' . today()->format('Y-m-d') . '|before:' . today()->addDays(90)->format('Y-m-d');
            $validation_messages['startdatum.before'] = 'Startdatumet får inte vara längre än 90 dagar fram.';
        }

        $validator = Validator::make($input, $rules, $validation_messages);

        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray()
            ];

            return response()->json($response);
        }

        // Get moment
        $focusapi = new FocusApi();
        // get nya foster and föl moment produkt 26
        if ($data['horse_usage'] == 2) {
            $focus_moments = collect($focusapi->get_moment(26));
        } else {
            // get moment
            $focus_moments = collect($focusapi->get_moment(22));
        }

        // Om kryssruta för safestart är satt, nyttja dem istället.
        if (
            (isset($input['veterinarvardsforsakring']) && $input['veterinarvardsforsakring'] == 38) &&
            (isset($input['livforsakring']) && $input['livforsakring'] == 38) &&
            $input['safestart'] == 1
        ) {
            $input['veterinarvardsforsakring'] = 40;
            $input['livforsakring'] = 41;
        }

        // Get labels
        $input['veterinarvardsforsakring_label'] = '';
        if (isset($input['veterinarvardsforsakring']) && !empty($input['veterinarvardsforsakring'])) {
            $input['veterinarvardsforsakring_label'] = $focus_moments->where('id', $input['veterinarvardsforsakring'])->first()['namn'] ?? '';
        }

        $input['livforsakring_label'] = '';
        if (isset($input['livforsakring']) && !empty($input['livforsakring'])) {
            $input['livforsakring_label'] = $focus_moments->where('id', $input['livforsakring'])->first()['namn'] ?? '';
        }

        // Store data
        $this->store_data($input);

        $next_step = 'halsodeklaration';

        // temp fix, to be removed
        if ($horse_usage == 2) {
            $next_step = 'sammanfattning';
            //$next_step = 'foster-o-fol';
        }
        // end temp fix

        return response()->json([
            'status' => 1,
            'next_step' => $next_step
        ]);
    }

    public function get_price($defaults = null)
    {
        $focusapi = new FocusApi();

        $data = $focusapi->get_shared_focus_data();

        if (!empty($defaults)) {
            $data['veterinarvardsforsakring'] = $defaults['veterinarvardsforsakring'];
            $data['veterinarvardsbelopp'] = $defaults['veterinarvardsbelopp'];
            $data['livforsakring'] = $defaults['livforsakring'];
            $data['livvarde'] = $defaults['livvarde'];
            $data['sjalvrisk'] = $defaults['sjalvrisk'] ?? '';
            $data['safestart'] = $defaults['safestart'] ?? 0;
            $data['startdatum'] = $defaults['startdatum'] ?? null;
            $data['swbmedlem'] = $defaults['swbmedlem'] ?? 'Nej';
            $data['stable'] = $defaults['stable'] ?? 'Nej';

            $forsakring_enabled = [];
            if (!empty($data['veterinarvardsforsakring'])) {
                $forsakring_enabled['vet'] = $data['veterinarvardsforsakring'];
            }
            if (!empty($data['livforsakring'])) {
                $forsakring_enabled['liv'] = $data['livforsakring'];
            }
            // Default data to above if not a request
            $data['forsakring_enabled'] = request()->get('forsakring_enabled', $forsakring_enabled);
        } elseif (request()->ajax()) {
            $data['veterinarvardsforsakring'] = request()->get('veterinarvardsforsakring');
            $data['veterinarvardsbelopp'] = request()->get('veterinarvardsbelopp');
            $data['livforsakring'] = request()->get('livforsakring');
            $data['livvarde'] = request()->get('livvarde');
            $data['sjalvrisk'] = request()->get('sjalvrisk');
            $data['forsakring_enabled'] = request()->get('forsakring_enabled', []);
            $data['safestart'] = request()->get('safestart', 0);
            $data['swbmedlem'] = request()->get('swbmedlem', 'Nej');
            $data['stable'] = request()->get('stable', 'Nej');
        }

        // get nya foster and föl moment produkt 26
        if ($data['horse_usage'] == 2) {
            $focus_moments = collect($focusapi->get_moment(26));
        } else {
            // get moment
            $focus_moments = collect($focusapi->get_moment(22));
        }

        // Om kryssruta för safestart är satt, nyttja dem istället.
        if (
            (isset($data['veterinarvardsforsakring']) && $data['veterinarvardsforsakring'] == 38) &&
            (isset($data['livforsakring']) && $data['livforsakring'] == 38) &&
            $data['safestart'] == 1
        ) {
            $data['veterinarvardsforsakring'] = 40;
            $data['livforsakring'] = 41;
        }

        // Sätt labels baserat från focus
        if (isset($data['veterinarvardsforsakring']) && !empty($data['veterinarvardsforsakring'])) {
            $data['veterinarvardsforsakring_label'] = $focus_moments->where('id', $data['veterinarvardsforsakring'])->first()['namn'] ?? '';
        }

        if (isset($data['livforsakring']) && !empty($data['livforsakring'])) {
            $data['livforsakring_label'] = $focus_moments->where('id', $data['livforsakring'])->first()['namn'] ?? '';
        }

        $moments = [];
        if (
            !empty($data['veterinarvardsforsakring']) &&
            array_key_exists('vet', $data['forsakring_enabled'])
        ) {
            $moments[] =  $data['veterinarvardsforsakring'];
        }
        if (
            !empty($data['livforsakring']) &&
            $data['veterinarvardsforsakring'] != $data['livforsakring'] &&
            array_key_exists('liv', $data['forsakring_enabled'])
        ) {
            $moments[] =  $data['livforsakring'];
        }

        $focus_fields = $focusapi->build_focus_fields($moments, $data);

        try {
            $termin = 1;
            // Foster och föl 12 månader
            if (isset($data['horse_usage']) && $data['horse_usage'] == 2) {
                $termin = 12;
            }

            $focus_price_response = $focusapi->get_pris(implode(',', $moments), $focus_fields, $data['civic_number'], $termin, null, $data['startdatum']);

            // Default variables for price
            $total_utpris = 0;
            $total_utpris_formated = 0;
            $total_total_utpris = 0;
            $total_total_formated_utpris = 0;
            $points = 0;

            // Monthly nad yearly
            if (isset($focus_price_response['utpris_per_termin'])) {
                $total_utpris = $focus_price_response['utpris_per_termin'];
                $total_total_utpris = $focus_price_response['utpris'];
            } else {
                foreach ($focus_price_response as $utpris) {
                    if (isset($utpris['utpris_per_termin'])) {
                        $total_utpris += $utpris['utpris_per_termin'];
                        $total_total_utpris += $utpris['utpris'];
                    }
                }
            }

            $total_utpris_formated = number_format($total_utpris, 0, ',', ' ') . ' kr/' . (($termin == 1) ? 'mån' : 'år');
            $total_total_formated_utpris = number_format($total_total_utpris, 0, ',', ' ');

            if ($total_total_utpris > 5000) {
                $points = 400;
            } else {
                $points = 200;
            }
        } catch (FocusApiException $e) {
            report($e);
            //throw $e;
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
                'compare_insurance' => $compare_insurance,
                'horse_usage' => $data['horse_usage'] ?? 0
            ])->render(),
                'html_boxes' => view('steps.horseinsurance.resultat.pris_boxes', [
                'data' => $data,
            ])->render(),
            'utpris' => $total_utpris,
            'utpris_formaterad' => $total_utpris_formated,
            'points' => $points
        ];
    }

    public function get_insurances()
    {

        $insurances = [
            4 => [  // vet
                'moment_id' => 4,
                'name' => 'Premium',
                'deductible_percentage' => [25, 50],
                'amount' => [
                    'from' => 50000,
                    'to'    => 150000
                ],
                'lifeinsurance_amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            6 => [ // vet
                'moment_id' => 6,
                'name' => 'Special',
                'deductible_percentage' => [25, 50],
                'amount' => [
                    'from' => 50000,
                    'to'    => 150000
                ],
                'lifeinsurance_amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            7 => [ // vet
                'moment_id' => 7,
                'name' => 'Breeding',
                'deductible_percentage' => [25, 50],
                'amount' => [
                    'from' => 50000,
                    'to'    => 150000
                ],
                'lifeinsurances' => [16]
            ],
            8 => [ // vet
                'moment_id' => 8,
                'name' => 'Grund',
                'deductible_percentage' => [25, 50],
                'amount' => [
                    'from' => 50000,
                    'to'    => 150000
                ],
                'lifeinsurance_amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            12 => [ // life
                'moment_id' => 12,
                'name' => 'Premium',
                'amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            13 => [ // life
                'moment_id' => 13,
                'name' => 'Special',
                'amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            14 => [ // vet
                'moment_id' => 14,
                'name' => 'Katastrof',
                'deductible_percentage' => [],
                'amount' => [
                    'from' => null,
                    'to'    => null
                ],
            ],
            16 => [ // vet
                'moment_id' => 16,
                'name' => 'Breeding',
                'amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            17 => [ // life
                'moment_id' => 17,
                'name' => 'Grund',
                'amount' => [
                    'from' => 15000,
                    'to'    => 500000
                ],
            ],
            /* old foster och föl
            20 => [ // vet
                'moment_id' => 20,
                'name' => 'Foster & föl',
                'deductible_percentage' => [25],
                'amount' => [
                    'from' => 100000,
                    'to'    => 100000
                ],
            ],
            */
            // nya foster och föl
            38 => [ // vet
                'moment_id' => 38,
                'name' => 'Foster & föl',
                'deductible_percentage' => [25],
                'amount' => [
                    'from' => 100000,
                    'to'    => 100000
                ],
            ],
        ];

        return $insurances;
    }
}
