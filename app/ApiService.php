<?php

namespace App;

use GuzzleHttp\Client;

class ApiService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'https://enterprise-api.writer.com/content/organization/599692/team/606618/check';
    }

    public function getSomeData()
    {
        $response = $this->client->get($this->baseUrl . '/endpoint', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.API_KEY'),
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
