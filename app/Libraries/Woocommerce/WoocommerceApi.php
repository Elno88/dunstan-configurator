<?php namespace App\Libraries\Woocommerce;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use App\Libraries\Woocommerce\WoocommerceApiException;

class WoocommerceApi
{
    /*
     * Woocommerce credentials
     */
    private $jwt;
    private $base_uri;

    /*
     * Guzzle client
     */
    private $client;

    /*
     * API URIs
     */
    const TEST_SERVER   = 'https://staging-dunstan-staging.kinsta.cloud/';
    const LIVE_SERVER   = 'https://dunstan.se/';

    /*
     * Construct client
     */
    function __construct()
    {
        $this->base_uri = config('services.woocommerce.live') ? self::LIVE_SERVER : self::TEST_SERVER;
        $this->jwt = config('services.woocommerce.live') ? config('services.woocommerce.jwt_live') : config('services.woocommerce.jwt_test');

        $this->client = new Client([
            'base_uri'      => $this->base_uri,
            'headers'       => [
                'Accept'        => 'application/json'
            ],
            'http_errors'   => false
        ]);

    }

    public function create_user($email, $password = null, $nickname = null)
    {
        $query = [
            'jwt' => $this->jwt,
            'rest_route' => '/jwt-auth/v1/users',
            'email' => $email
        ];

        if(!empty($password)){
            $query['password'] = $password;
        }

        if(!empty($nickname)){
            $query['nickname'] = $nickname;
        }

        $response = $this->client->post('', [
            'form_params' => $query
        ]);

        if(!in_array($response->getStatusCode(),[200, 201])){
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
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
                $message = 'Unknown error.';
        }
        throw new WoocommerceApiException($message, $response->getStatusCode());
    }

}
