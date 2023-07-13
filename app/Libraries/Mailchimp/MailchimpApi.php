<?php namespace App\Libraries\Mailchimp;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use App\Libraries\Mailchimp\MailchimpApiException;

class MailchimpApi
{
    /*
     * Mailchimp credentials
     */
    private $dc;

    private $key;

    private $list;

    /*
     * Guzzle client
     */
    private $client;

    /*
     * Construct client
     */
    function __construct()
    {
        $this->dc = config('services.mailchimp.live') ? config('services.mailchimp.dc_live') : config('services.mailchimp.dc_test');
        $this->key = config('services.mailchimp.live') ? config('services.mailchimp.key_live') : config('services.mailchimp.key_test');
        $this->list = config('services.mailchimp.live') ? config('services.mailchimp.list_live') : config('services.mailchimp.list_test');

        $base_url = 'https://'.$this->dc.'.api.mailchimp.com/3.0/';

        $this->client = new Client([
            'base_uri'      => $base_url,
            'headers'       => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ],
            'auth' => [
                'user',
                $this->key
            ],
            'http_errors'   => false
        ]);

    }


    public function subscribe_member($email, $meta = [], $status = 'subscribed')
    {

        $uri = 'lists/'.$this->list.'/members/'.md5(strtolower($email));

        $query = [
            'email_address' => $email,
            'status' => $status,
        ];

        if(!empty($meta)){
            $query['merge_fields'] = $meta;
        }

        $response = $this->client->put($uri, [
            'json' => $query
        ]);

        if(!in_array($response->getStatusCode(),[200, 201, 204])){
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data ?? [];
    }

    public function member_assign_tags($email, $tags = [])
    {
        $uri = 'lists/'.$this->list.'/members/'.md5(strtolower($email)).'/tags';

        $new_tags = [];
        foreach($tags as $tag){
            $new_tags[] = [
                'name' => $tag,
                'status' => 'active'
            ];
        }

        $response = $this->client->post($uri, [
            'json' => [
                'tags' => $new_tags,
            ]
        ]);

        if(!in_array($response->getStatusCode(),[200, 201, 204])){
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
                $message = 'MC Unknown error.';
        }

        throw new MailchimpApiException($message, $response->getStatusCode());
    }

}
