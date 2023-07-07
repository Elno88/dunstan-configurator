<?php

namespace App\Services\Biluppgifter;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Biluppgifter
{
    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
        $this->client = Http::baseUrl($this->getBaseUrl())
            ->withToken($this->getBearerToken())
            ->acceptJson()
            ->asJson();
    }

    /**
     * Finds a vehicle by register no.
     *
     * @param  string     $regno
     * @param  array      $packages
     * @param  bool       $active
     * @param  string     $country
     *
     * @throws \Exception
     *
     * @return null|json
     */
    public function findByRegNo(string $regno, array $packages = [], bool $active = false, string $country = null)
    {
        $endpoint = sprintf('vehicle/regno/%s', $regno);

        $response = $this->client->get($endpoint, [
            'active'       => (int) $active,
            'include'      => implode(',', $packages),
            'country_code' => Str::upper($country ?? 'SE'),
        ]);

        if ($response->failed() && $response->status() !== 404) {
            throw new \Exception($response->json('message'));
        } elseif ($response->failed()) {
            return null;
        }

        return $response->json('data');
    }

    /**
     * Gets the base url.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return config('services.biluppgifter.url');
    }

    /**
     * Gets the bearer token.
     *
     * @return string
     */
    protected function getBearerToken(): string
    {
        return config('services.biluppgifter.token');
    }
}
