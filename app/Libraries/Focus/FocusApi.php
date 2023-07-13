<?php

namespace App\Libraries\Focus;

use App\Libraries\Papilite\PapiliteApi;
use App\Libraries\Papilite\PapiliteApiException;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Cache;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FocusApi
{
    /*
     * Focus credentials
     */
    private $username;
    private $password;
    private $jwt;
    private $anstalld;
    private $base_uri;
    private $jwt_cache;
    private $jwt_cache_lifetime;

    /*
     * Guzzle client
     */
    private $client;

    /*
     * API URIs
     */
    const TEST_SERVER = 'https://test-dunstan.jaycom.se/';
    const LIVE_SERVER = 'https://dunstan.jaycom.se/';

    /*
     * Construct client
     */
    function __construct()
    {
        $this->base_uri = config('services.focus.live') ? self::LIVE_SERVER : self::TEST_SERVER;
        $this->anstalld = config('services.focus.live') ? config('services.focus.anstalld_live') : config('services.focus.anstalld_test');
        $this->username = config('services.focus.live') ? config('services.focus.username_live') : config('services.focus.username_test');
        $this->password = config('services.focus.live') ? config('services.focus.password_live') : config('services.focus.password_test');

        $this->base_uri = str_replace(" ", "", self::TEST_SERVER);

        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Accept' => 'application/json'
            ],
            'http_errors' => false
        ]);

        // Get jwt and cache
        $this->jwt_cache = config('services.focus.live') ? 'focus_jwt_live' : 'focus_jwt_test';

        if (config('services.focus.live')) {
            $this->jwt_cache_lifetime = 2160000; // 25 dagar
        } else {
            $this->jwt_cache_lifetime = 28800; // 8 timmar
        }

        $this->jwt = Cache::remember($this->jwt_cache, $this->jwt_cache_lifetime, function () {
            return $this->login($this->username, $this->password)['jwt'] ?? '';
        });

        echo "base_uri: " . $this->base_uri . " - " . $this->username . " - " . $this->password . " - " . $this->anstalld . " - " . $this->jwt . "\n";

    }

    // Get jwt
    public function login($username, $password)
    {
        $query = [
            'rest' => 1,
            'do' => 'auth.login',
            'user' => $username,
            'pass' => $password,
            'cookie' => 0
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_address($civic_number)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.getAdressuppgift',
            'persnr' => $civic_number
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function create_ticket($customer_id, $name, $comment, $list = 1, $enddate = null, $tags = [])
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'pm.arende.api.skapa',
            'kund' => $customer_id,
            'namn' => $name,
            'kommentar' => $comment,
            'lista' => $list
        ];

        if (!is_null($enddate)) {
            $query['forfallodatum'] = $enddate;
        }

        if (!empty($tags)) {
            $query['tags'] = $tags;
        }

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function add_ticket_file($ticket_id, $name, $file, $public = 0, $date = null)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'pm.arende.api.sparaFil',
            'arende' => $ticket_id,
            'namn' => $name,
            'publik' => $public,
            'data' => $file,
        ];

        if (!is_null($date)) {
            $query['datum'] = $date;
        }

        $response = $this->client->post('', [
            'form_params' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function delete_ticket_file($file_id)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'pm.arende.api.deleteFil',
            'id' => $file_id,
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function add_points($customer_id, $points, $horse_name)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'dunstan.poang.spara',
            'kund' => $customer_id,
            'poang' => $points,
            'notering' => 'Webbteckning: ' . $horse_name,
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_prislista()
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.getPrislistor'
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function update_customer($civic_number, $data = [], $update = 1)
    {
        return $this->create_customer($civic_number, $data, $update);
    }

    public function create_customer($civic_number, $data = [], $update = 0)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'crm.kund.spara',
            'persnr' => $civic_number,
            'uppdatera' => $update
        ];

        foreach ($data as $key => $d) {
            $query[$key] = $d;
        }

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_customer($civic_number = null, $customer_id = null, $customer_number = null)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.getKund'
        ];

        if (!is_null($civic_number)) {
            $query['persnr'] = $civic_number;
        }

        if (!is_null($customer_id)) {
            $query['id'] = $customer_id;
        }

        if (!is_null($customer_number)) {
            $query['kundnr'] = $customer_number;
        }

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_questions($type = 1)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'undersokning.api.fragor',
            'undersokning' => $type
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function save_questions($customer_id, $questions, $type = 1, $preview = 0)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'undersokning.api.spara',
            'kund' => $customer_id,
            'undersokning' => $type,
            'preview' => $preview,
            'svar' => $questions
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_moment($moment_id)
    {

        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.getMoment',
            'produkt' => $moment_id
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_pris(
        $moment_ids,
        array $fields,
        $civic_number,
        int $paymentterm_months,
        $pricelist = null,
        $start_date = null
    ) {

        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.getPris',
            'moment' => $moment_ids,
            'falt' => $fields,
            'betalningstermin' => $paymentterm_months
        ];

        if (!is_null($civic_number)) {
            $query['persnr'] = $civic_number;
        }

        if (!is_null($pricelist)) {
            $query['prislista'] = $pricelist;
        }

        if (!is_null($start_date)) {
            $query['anslutsdatum'] = $start_date;
        }

        // pre($query);

        $response = $this->client->get('', [
            'query' => $query
        ]);

        // pre((string) $response->getBody());

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    /**
     * @throws \App\Libraries\Focus\FocusApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function bankid_sign($civic_number, $show_text = '', $hidden_text = '', $async = 0)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.bankidSignera',
            'persnr' => $civic_number,
            'synligText' => $show_text,
            'doldText' => $hidden_text,
            'async' => $async
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function bankid_login_start($civic_number)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.bankidLogin',
            'persnr' => $civic_number
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function bankid_login_check($reference)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.bankidStatus',
            'orderRef' => $reference
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function valid_autogiro_account($clearing, $account)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'fakturera.mottagare.api.isBanknummer',
            'clearingnr' => $clearing,
            'kontonr' => $account
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function register_autogiro($customer_id, $clearing, $account)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'fakturera.mottagare.api.autogiro',
            'id' => $customer_id,
            'clearingnr' => $clearing,
            'kontonr' => $account
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function add_note_to_customer($customer_id, $note = '')
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'crm.noteringar.api.spara',
            'kund' => $customer_id,
            'notering' => $note
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function deactivate_autogiro($customer_id)
    {
        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'fakturera.mottagare.api.autogiro',
            'id' => $customer_id
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function add_insurance(
        $customer_id,
        $moment_ids,
        array $fields,
        $svarande_id = null,
        $anstalld = null,
        $paymentterm_months = null,
        $pricelist = null,
        $start_date = null,
        $invoicestatus = null,
        $notes = null,
    ) {

        // Sätt förmedlare
        if (empty($anstalld)) {
            $anstalld = $this->anstalld;
        }

        $query = [
            'rest' => 1,
            'jwt' => $this->jwt,
            'do' => 'forsakring.api.anslut',
            'forsakrad' => $customer_id,
            'premiebetalare' => $customer_id,
            'moment' => $moment_ids,
            'falt' => $fields,
            'betalningstermin' => $paymentterm_months,
            'anstalld' => $anstalld,
        ];

        if (!is_null($invoicestatus)) {
            $query['fakturastatus'] = $invoicestatus;
        }

        if (!is_null($pricelist)) {
            $query['prislista'] = $pricelist;
        }

        if (!is_null($start_date)) {
            $query['anslutsdatum'] = $start_date;
        }

        if (!is_null($svarande_id)) {
            $query['svarande'] = $svarande_id;
        }

        if (!is_null($notes)) {
            $query['notering'] = $notes;
        }

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function get_shared_focus_data()
    {
        // get data from session
        $session_data = session()->get('steps', []);

        // hästförsäkring a linjen
        if (
            isset($session_data['data']['hastforsakring']['hastforsakring']) &&
            $session_data['data']['hastforsakring']['hastforsakring'] == 'hastforsakring-a-1'
        ) {
            $data = [
                // Session id
                'session_id' => $session_data['session_id'] ?? null,
                'step_insurance' => 'hastforsakring-a-1',

                'horse_usage' => $session_data['data']['hastforsakring-a-1']['horse_usage'] ?? null,
                'horse_usage_label' => $session_data['data']['hastforsakring-a-1']['horse_usage_label'] ?? null,
                'fodelsedatum' => $session_data['data']['hastforsakring-a-2']['fodelsedatum'] ?? null,
                'age' => $session_data['data']['hastforsakring-a-2']['age'] ?? null,
                'gender' => $session_data['data']['hastforsakring-a-3']['gender'] ?? '',
                'namn' => $session_data['data']['hastforsakring-a-4']['namn'] ?? '',
                'breed' => $session_data['data']['hastforsakring-a-5']['breed'] ?? null,
                'folningdatum' => $session_data['data']['hastforsakring-a-6']['folningdatum'] ?? '',
                'born' => $session_data['data']['hastforsakring-a-8']['born'] ?? '',
                'born_risk' => $session_data['data']['hastforsakring-a-8']['risk'] ?? null,
                'civic_number' => $session_data['data']['hastforsakring-a-9']['civic_number'] ?? null,
                'state' => $session_data['data']['hastforsakring-a-9']['state'] ?? 'Okänt',


                // Nya fält för foster o föl (DFF)
                'stallion_name' => $session_data['data']['hastforsakring-a-ff-betackning']['stallion_name'] ?? null,
                'seminstation' => $session_data['data']['hastforsakring-a-ff-betackning']['seminstation'] ?? null,
                'stallion_covering_type' => $session_data['data']['hastforsakring-a-ff-betackning']['stallion_covering_type'] ?? null,
                'insurance_type' => $session_data['data']['hastforsakring-a-ff-forsakring']['insurance_type'] ?? null,

                // Resultat
                'veterinarvardsforsakring' => $session_data['data']['resultat']['veterinarvardsforsakring'] ?? null,
                'veterinarvardsforsakring_label' => $session_data['data']['resultat']['veterinarvardsforsakring_label'] ?? null,
                'veterinarvardsbelopp' => $session_data['data']['resultat']['veterinarvardsbelopp'] ?? null,
                'livforsakring' => $session_data['data']['resultat']['livforsakring'] ?? null,
                'livforsakring_label' => $session_data['data']['resultat']['livforsakring_label'] ?? null,
                'livvarde' => $session_data['data']['resultat']['livvarde'] ?? null,
                'sjalvrisk' => $session_data['data']['resultat']['sjalvrisk'] ?? null,
                'forsakring_enabled' => $session_data['data']['resultat']['forsakring_enabled'] ?? null,
                'safestart' => $session_data['data']['resultat']['safestart'] ?? null,
                'uppsagning' => $session_data['data']['resultat']['uppsagning'] ?? null,
                'swbmedlem' => $session_data['data']['resultat']['swbmedlem'] ?? null,

                'stable'                    => $session_data['data']['resultat']['stable'] ?? null,

                // Hälsodeklaration
                'questions' => $session_data['data']['halsodeklaration']['questions'] ?? [],
                'document_type' => $session_data['data']['halsodeklaration']['document_type'] ?? null,

                // Sammanfattning
                'startdatum' => $session_data['data']['sammanfattning']['startdatum'] ?? $session_data['data']['resultat']['startdatum'] ?? today()->format('Y-m-d'),
                'email' => $session_data['data']['sammanfattning']['email'] ?? $session_data['data']['hastforsakring-a-10']['email'] ?? null,
                'telefon' => $session_data['data']['sammanfattning']['telefon'] ?? $session_data['data']['hastforsakring-a-10']['telefon'] ?? '',
                'betalningsmetod' => $session_data['data']['sammanfattning']['betalningsmetod'] ?? 'faktura',
                'betalningstermin' => $session_data['data']['sammanfattning']['betalningstermin'] ?? 12,
                'autogiro_clearing' => $session_data['data']['sammanfattning']['autogiro_clearing'] ?? null,
                'autogiro_account' => $session_data['data']['sammanfattning']['autogiro_account'] ?? null,
                'chip_number' => $session_data['data']['sammanfattning']['chip_number'] ?? $session_data['data']['hastforsakring-a-7']['chip_number'] ?? '',

                // Tack
                'completed_products' => $session_data['data']['tack']['products'] ?? [],
            ];
        } elseif (
            isset($session_data['data']['hastforsakring']['hastforsakring']) &&
            $session_data['data']['hastforsakring']['hastforsakring'] == 'hastforsakring-b-1'
        ) {
            // hästförsäkring b linjen
            $data = [
                // Session id
                'session_id' => $session_data['session_id'] ?? null,
                'step_insurance' => 'hastforsakring-b-1',

                'horse_usage' => $session_data['data']['hastforsakring-b-3']['horse_usage'] ?? null,
                'horse_usage_label' => $session_data['data']['hastforsakring-b-3']['horse_usage_label'] ?? null,
                'fodelsedatum' => $session_data['data']['hastforsakring-b-4']['fodelsedatum'] ?? null,
                'age' => $session_data['data']['hastforsakring-b-4']['age'] ?? null,
                'gender' => $session_data['data']['hastforsakring-b-5']['gender'] ?? '',
                'namn' => $session_data['data']['hastforsakring-b-6']['namn'] ?? '',
                'breed' => $session_data['data']['hastforsakring-b-7']['breed'] ?? null,
                'folningdatum' => $session_data['data']['hastforsakring-b-8']['folningdatum'] ?? '',
                'born' => $session_data['data']['hastforsakring-b-10']['born'] ?? '',
                'born_risk' => $session_data['data']['hastforsakring-b-10']['risk'] ?? null,
                'civic_number' => $session_data['data']['hastforsakring-b-11']['civic_number'] ?? null,
                'state' => $session_data['data']['hastforsakring-b-11']['state'] ?? 'Okänt',

                // Nya fält för foster o föl (DFF)
                'stallion_name' => $session_data['data']['hastforsakring-b-ff-betackning']['stallion_name'] ?? null,
                'seminstation' => $session_data['data']['hastforsakring-b-ff-betackning']['seminstation'] ?? null,
                'stallion_covering_type' => $session_data['data']['hastforsakring-b-ff-betackning']['stallion_covering_type'] ?? null,
                'insurance_type' => $session_data['data']['hastforsakring-b-ff-forsakring']['insurance_type'] ?? null,

                // Resultat

                'veterinarvardsforsakring' => $session_data['data']['resultat']['veterinarvardsforsakring'] ?? null,
                'veterinarvardsforsakring_label' => $session_data['data']['resultat']['veterinarvardsforsakring_label'] ?? null,
                'veterinarvardsbelopp' => $session_data['data']['resultat']['veterinarvardsbelopp'] ?? null,
                'livforsakring' => $session_data['data']['resultat']['livforsakring'] ?? null,
                'livforsakring_label' => $session_data['data']['resultat']['livforsakring_label'] ?? null,
                'livvarde' => $session_data['data']['resultat']['livvarde'] ?? null,
                'sjalvrisk' => $session_data['data']['resultat']['sjalvrisk'] ?? null,
                'forsakring_enabled' => $session_data['data']['resultat']['forsakring_enabled'] ?? null,
                'safestart' => $session_data['data']['resultat']['safestart'] ?? null,
                'uppsagning' => $session_data['data']['resultat']['uppsagning'] ?? null,
                'swbmedlem' => $session_data['data']['resultat']['swbmedlem'] ?? null,

                'stable'                    => $session_data['data']['resultat']['stable'] ?? null,

                // Hälsodeklaration
                'questions' => $session_data['data']['halsodeklaration']['questions'] ?? [],
                'document_type' => $session_data['data']['halsodeklaration']['document_type'] ?? null,

                // Sammanfattning
                'startdatum' => $session_data['data']['sammanfattning']['startdatum'] ?? $session_data['data']['resultat']['startdatum'] ?? today()->format('Y-m-d'),
                'email' => $session_data['data']['sammanfattning']['email'] ?? $session_data['data']['hastforsakring-b-12']['email'] ?? null,
                'telefon' => $session_data['data']['sammanfattning']['telefon'] ?? $session_data['data']['hastforsakring-b-12']['telefon'] ?? '',
                'betalningsmetod' => $session_data['data']['sammanfattning']['betalningsmetod'] ?? 'faktura',
                'betalningstermin' => $session_data['data']['sammanfattning']['betalningstermin'] ?? 12,
                'autogiro_clearing' => $session_data['data']['sammanfattning']['autogiro_clearing'] ?? null,
                'autogiro_account' => $session_data['data']['sammanfattning']['autogiro_account'] ?? null,
                'chip_number' => $session_data['data']['sammanfattning']['chip_number'] ?? $session_data['data']['hastforsakring-b-9']['chip_number'] ?? '',

                // Tack
                'completed_products' => $session_data['data']['tack']['products'] ?? [],
            ];

            if (
                isset($session_data['data']['hastforsakring-b-1']['insurances']) &&
                isset($session_data['data']['hastforsakring-b-2']['insurance']) &&
                isset($session_data['data']['hastforsakring-b-1']['insurances'][$session_data['data']['hastforsakring-b-2']['insurance']])
            ) {
                $data['compare_insurance'] = $session_data['data']['hastforsakring-b-1']['insurances'][$session_data['data']['hastforsakring-b-2']['insurance']];
            }
        } else {
            $data = [
                'civic_number' => $session_data['data']['customer']['kund']['persnr'] ?? null,
                'startdatum' => $session_data['data']['trailerforsakring-sammanfattning']['startdatum'] ?? $session_data['data']['resultat']['startdatum'] ?? today()->format('Y-m-d'),
                'email' => $session_data['data']['trailerforsakring-sammanfattning']['email'] ?? $session_data['data']['hastforsakring-a-10']['email'] ?? null,
                'telefon' => $session_data['data']['trailerforsakring-sammanfattning']['telefon'] ?? $session_data['data']['hastforsakring-a-10']['telefon'] ?? '',
                'betalningsmetod' => $session_data['data']['trailerforsakring-sammanfattning']['betalningsmetod'] ?? 'faktura',
                'betalningstermin' => $session_data['data']['trailerforsakring-sammanfattning']['betalningstermin'] ?? 12,
                'autogiro_clearing' => $session_data['data']['trailerforsakring-sammanfattning']['autogiro_clearing'] ?? null,
                'autogiro_account' => $session_data['data']['trailerforsakring-sammanfattning']['autogiro_account'] ?? null,
            ];

            // Get state / Län based on zipcode
            try {
                // Format zip, remove whitespaces
                $zip_code = preg_replace("/\s+/", "", $session_data['data']['customer']['kund']['postnr'] ?? '');

                // Use Papiliteapi to get state based on zip
                $papilite = new PapiliteApi();
                $papilite_address = $papilite->get_address_from_zip($zip_code);

                // convert stupid state
                $focus_state = $this->convert_state_to_focus($papilite_address['state'] ?? '');

                // if we have a state, set it
                if(isset($focus_state) && !empty($focus_state)){
                    $data['state'] = $focus_state;
                } else {
                    // On error, default state tå Okänt
                    $data['state'] = 'Okänt';
                }

            } catch (PapiliteApiException $e) {
                report($e);
                // On error, default state to Okänt
                $data['state'] = 'Okänt';
            }

        }

        return $data;
    }

    // Build focus fields data
    public function build_focus_fields($moments, $data)
    {
        $fields = [];

        foreach ($moments as $moment) {

            $new_fields = [];

            switch ($moment) {
                case 4:
                    // Premium vetrinärvård
                    $new_fields = [
                        5 => $data['breed'], // Ras
                        9 => $data['gender'], // Kön
                        10 => $data['age'], // Ålder
                        11 => $data['fodelsedatum'], // Född
                        12 => $data['veterinarvardsbelopp'], // Veterinarvårdbelopp
                        13 => $data['sjalvrisk'], // Självrisk veterinärvård självrisk
                        14 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        105 => $data['namn'], // Namn
                        15 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        104 => 'Nej', // Moms
                        8 => $data['state'] ?? 'Skåne', // Län
                        //7 => '0', // Rasgrupp
                        //183 => $data['farg'], // Färg
                        //548 => $data['stable'], // Uppstallning

                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[107] = $data['chip_number'];
                    }
                    break;
                case 6:
                    // Special Veterinärvård
                    $new_fields = [
                        26 => $data['breed'], // Ras
                        29 => $data['gender'], // Kön
                        30 => $data['age'], // Ålder
                        31 => $data['fodelsedatum'], // Född
                        32 => $data['veterinarvardsbelopp'], // Veterinarvårdbelopp
                        33 => $data['sjalvrisk'], // Självrisk veterinärvård självrisk
                        34 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        109 => $data['namn'], // Namn
                        35 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        139 => 'Nej', // Moms
                        28 => $data['state'] ?? 'Okänt', // Län
                        // 27 => '0', // Rasgrupp
                        // 120 => '', Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[121] = $data['chip_number'];
                    }
                    break;
                case 7:
                    // Breeding Veterinärvård
                    $new_fields = [
                        36 => $data['breed'], // Ras
                        39 => $data['gender'], // Kön
                        40 => $data['age'], // Ålder
                        41 => $data['fodelsedatum'], // Född
                        42 => $data['veterinarvardsbelopp'], // Veterinarvårdbelopp
                        43 => $data['sjalvrisk'], // Självrisk veterinärvård självrisk
                        44 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        110 => $data['namn'], // Namn
                        45 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        140 => 'Nej', // Moms
                        38 => $data['state'] ?? 'Okänt', // Län
                        // 43 => '0', // Rasgrupp
                        // 122 => '', Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[123] = $data['chip_number'];
                    }
                    break;
                case 8:
                    // Grund veterinärförsäkring
                    $new_fields = [
                        46 => $data['breed'], // Ras
                        49 => $data['gender'], // Kön
                        50 => $data['age'], // Ålder
                        51 => $data['fodelsedatum'], // Född
                        52 => $data['veterinarvardsbelopp'], // Veterinarvårdbelopp
                        53 => $data['sjalvrisk'], // Självrisk veterinärvård självrisk
                        54 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        111 => $data['namn'], // Namn
                        55 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        141 => 'Nej', // Moms
                        48 => $data['state'] ?? 'Okänt', // Län
                        // 47 => 0, // Rasgrupp
                        // 124 => '', // Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[125] = $data['chip_number'];
                    }
                    break;
                case 12:
                    // Premium Liv & Användbarhet
                    $new_fields = [
                        67 => $data['breed'], // Ras
                        71 => $data['age'], // Ålder
                        72 => $data['fodelsedatum'], // Född
                        77 => $data['livvarde'], // Försäkringsbelopp
                        112 => $data['namn'], // Namn
                        176 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        177 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        142 => 'Nej', // Moms
                        361 => 0, // Minimipremie
                        // 68 => '', // Rasgrupp
                        // 126 => '', // Färg

                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[127] = $data['chip_number'];
                    }
                    break;
                case 13:
                    // Special Liv & Användbarhet
                    $new_fields = [
                        78 => $data['breed'], // Ras
                        80 => $data['age'], // Ålder
                        81 => $data['fodelsedatum'], // Född
                        82 => $data['livvarde'], // Försäkringsbelopp
                        113 => $data['namn'], // Namn
                        175 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        178 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        143 => 'Nej', // Moms
                        362 => 0, // Minimipremie
                        // 79 => '', // Rasgrupp
                        // 128 => '', // Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[129] = $data['chip_number'];
                    }
                    break;
                case 14:
                    // Katastrofförsäkring
                    $new_fields = [
                        83 => $data['breed'], // Ras
                        85 => $data['age'], // Ålder
                        86 => $data['fodelsedatum'], // Född
                        87 => $data['livvarde'], // Försäkringsbelopp
                        117 => $data['namn'], // Namn
                        171 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        182 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        147 => 'Nej', // Moms
                        // 84 => '', // Rasgrupp
                        // 136 => '', // Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[137] = $data['chip_number'];
                    }
                    break;
                case 16:
                    // Breeding Liv & Användbarhet
                    $new_fields = [
                        93 => $data['breed'], // Ras
                        95 => $data['age'], // Ålder
                        96 => $data['fodelsedatum'], // Född
                        97 => $data['livvarde'], // Försäkringsbelopp
                        115 => $data['namn'], // Namn
                        174 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        179 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        145 => 'Nej', // Moms
                        364 => 0, // Minimipremie
                        // 94 => '', // Rasgrupp
                        // 132 => '', // Färg
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[133] = $data['chip_number'];
                    }
                    break;
                case 17:
                    // Grund Livsförsäkring
                    $new_fields = [
                        98 => $data['breed'], // Ras
                        100 => $data['age'], // Ålder
                        101 => $data['fodelsedatum'], // Född
                        102 => $data['livvarde'], // Försäkringsbelopp
                        116 => $data['namn'], // Namn
                        173 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                        180 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                        146 => 'Nej', // Moms
                        365 => 0, // Minimipremie
                        // 134 => 'Färg', // Färg
                        // 99 => 'Rasgrupp', // Rasgrupp
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[133] = $data['chip_number'];
                    }
                    break;

                /* old Foster o föl
            case 20:
                // Foster o föl
                $new_fields = [
                    162 => $data['breed'], // Ras
                    164 => $data['age'], // Ålder
                    165 => $data['fodelsedatum'], // Född
                    166 => $data['livvarde'], // Försäkringsbelopp
                    167 => $data['namn'], // Namn
                    172 => (empty($data['born']) || $data['born'] == 'Ja' ? 'Nej' : 'Ja'), // Import
                    181 => $data['swbmedlem'] ?? 'Nej', // SWB registrerad, alt, Ja, Ja - Unghäst, Nej
                    170 => 'Nej', // Moms
                    366 => 0, // Minimipremie
                    //163 => 'Rasgrupp', // Rasgrupp
                    //168 => 'Svart', // Färg
                    //169 => '', // Chip-nummer / ID-nummer"
                ];
                if(!empty($data['chip_number'])){
                    $new_fields[169] = $data['chip_number'];
                }
                break;
            */

                case 38:
                    // Nya Foster o föl (DFF)
                    $new_fields = [
                        445 => $data['breed'], // Ras
                        446 => $data['fodelsedatum'], // Född
                        448 => $data['namn'], // Namn
                        451 => 'Nej', // Moms //
                        456 => 0, // Minimipremie
                        454 => $data['folningdatum'], // Beräknad fölning
                        457 => $data['stallion_name'], // Betäckt med hingst
                        459 => $data['stallion_covering_type'], // Typ av betäckning
                        460 => $data['seminstation'], // Seminstation
                        447 => $data['livvarde'], // Försäkringsbelopp
                        462 => $data['veterinarvardsbelopp'], // Veterinärvård
                        455 => $data['insurance_type'] // Stoets försäkring
                        // 463 => $data['sjalvrisk'].' %' // Självrisk veterinärvård
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[450] = $data['chip_number'];
                    }
                    break;

                    // Dessa nedan kan enbart användas i kombination med varandra
                case 40:
                    // Safe start Föl (kan endast användas o kombination med Safe start foster)
                    $new_fields = [
                        489 => $data['breed'], // ras
                        494 => $data['fodelsedatum'], // födelsedatum
                        500 => $data['namn'], // stoets namn
                        499 => 'Nej', // moms
                        507 => $data['folningdatum'], // beräknad fölning
                        504 => $data['stallion_name'], // betäckt med hingst
                        506 => $data['stallion_covering_type'], // typ av betäckning
                        510 => $data['seminstation'], // seminstation
                        508 => 5000, // statiskt värde på 5k försäkringsbelopp
                        495 => $data['veterinarvardsbelopp'], // Veterinärvård
                        496 => $data['sjalvrisk'], // Självrisk veterinärvård
                        509 => $data['insurance_type'], // stoet försäkrad
                        493 => $data['age'], // Ålder
                        530 => 'Nej', // Foster & föl i annat bolag
                        491 => $data['state'] ?? 'Okänt', // Län
                        // Nedan borde inte krävas
                        // 511 => '', // Fölets namn
                        // 492 => '', // kön
                        // 490 => '', // rasgrupp
                        // 512 => '', // Import
                        // 498 => '', // SWB Registrerad
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[501] = $data['chip_number'];
                    }
                    break;
                case 41:
                    // Safe start Foster (kan endast användas o kombination med Safe start Föl)
                    $new_fields = [
                        513 => $data['breed'], // ras
                        514 => $data['fodelsedatum'], // födelsedatum
                        516 => $data['namn'], // stoets namn
                        518 => 'Nej', // moms
                        521 => 0, // minimipremie
                        519 => $data['folningdatum'], // beräknad fölning
                        522 => $data['stallion_name'], // betäckt med hingst
                        524 => $data['stallion_covering_type'], // typ av betäckning
                        525 => $data['seminstation'], // seminstation
                        515 => $data['livvarde'], // försäkringsbelopp
                        520 => $data['insurance_type'], // stoet försäkrad
                    ];
                    if (!empty($data['chip_number'])) {
                        $new_fields[517] = $data['chip_number'];
                    }
                    break;

                case 47:
                    $vehicle = session()->get('steps.data.vehicle', []);
                    $customer = session()->get('steps.data.customer', []);
                    $options = session()->get('steps.data.options', []);

                    if(!isset($customer['kund']['namn'])) {
                        $customer['kund']['namn'] = $customer['kund']['fornamn'];
                    }

                    $new_fields = [
                        652 => $vehicle['regnr'],
                        653 => $vehicle['make'],
                        654 => $vehicle['model'],
                        655 => $vehicle['year'],
                        656 => $vehicle['total_weight'],
                        657 => $vehicle['service_weight'],
                        658 => '',
                        659 => $options['form'],
                        660 => $options['safety'],
                        661 => $options['benefit'],
                        663 => 1500,
                        670 => $data['state'],
                        671 => $customer['kund']['namn'].' '.$customer['kund']['efternamn'],
                    ];
                    break;
            }

            $fields = $fields + $new_fields;
        }

        return $fields;
    }

    public function convert_state_to_focus($state)
    {
        switch ($state) {
            case 'Blekinge':
                $new_state = 'Blekinge';
                break;
            case 'Dalarna':
                $new_state = 'Dalarnas';
                break;
            case 'Gävleborg':
                $new_state = 'Gävleborgs';
                break;
            case 'Gotland':
                $new_state = 'Gotlands';
                break;
            case 'Halland':
                $new_state = 'Hallands';
                break;
            case 'Jönköping':
                $new_state = 'Jönköpings';
                break;
            case 'Kalmar':
            case 'Kronobergs':
                $new_state = 'Kalmar och Kronoberg';
                break;
            case 'Norrbotten':
                $new_state = 'Norrbottens';
                break;
            case 'Östergötland':
                $new_state = 'Östergötlands';
                break;
            case 'Skåne':
                $new_state = 'Skåne';
                break;
            case 'Södermanland':
                $new_state = 'Södermanlands';
                break;
            case 'Stockholm':
                $new_state = 'Stockholms';
                break;
            case 'Uppsala':
                $new_state = 'Uppsala';
                break;
            case 'Värmland':
                $new_state = 'Värmlands';
                break;
            case 'Västerbotten':
                $new_state = 'Västerbottens';
                break;
            case 'Västernorrland':
            case 'Jämtland':
                $new_state = 'Västernorrlands och Jämtland';
                break;
            case 'Västmanland':
            case 'Örebro':
                $new_state = 'Västmanlands och Örebro län';
                break;
            case 'Västra Götaland':
                $new_state = 'Västra Götalands';
                break;
            default:
                $new_state = null;
        }
        return $new_state;
    }

    /*
     * Handle Errors
     * */
    private function handleError($response)
    {
        switch ($response->getStatusCode()) {
            case 400:
            case 404:
            case 412:
            case 500:
                $message = $response->getBody();
                break;
            default:
                $message = 'Unknown error. Code: ' . $response->getStatusCode();
        }

        throw new FocusApiException($message, $response->getStatusCode());
    }
}
