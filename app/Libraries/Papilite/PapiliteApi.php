<?php namespace App\Libraries\Papilite;

use GuzzleHttp\Client;

use App\Libraries\Papilite\PapiliteApiException;

class PapiliteApi
{

    private $key;

    /*
     * Guzzle client
     */
    private $client;

    /*
     * Construct client
     */
    function __construct()
    {
        $this->key = config('services.papilite.live') ? config('services.papilite.key_live') : config('services.papilite.key_test');

        $base_url = 'https://api.papapi.se/lite/';

        $this->client = new Client([
            'base_uri'      => $base_url,
            'headers'       => [
                'Accept'        => 'application/json'
            ],
            'http_errors'   => false
        ]);
    }

    public function get_address_from_zip($zip)
    {
        $query = [
            'query' => $zip,
            'format' => 'json',
            'apikey' => $this->key
        ];

        $response = $this->client->get('', [
            'query' => $query
        ]);

        if(!in_array($response->getStatusCode(),[200, 201, 204])){
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data['results'][0] ?? [];
    }

    /*
     * Handle Errors
     * */
    private function handleError($response)
    {
        switch($response->getStatusCode()){
            case 400:
            case 404:
            case 412:
            case 500:
                $message = $response->getBody();
                break;
            default:
                $message = 'Papilite Unknown error. Code: ' . $response->getStatusCode();
        }

        throw new PapiliteApiException($message, $response->getStatusCode());
    }

}
