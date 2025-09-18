<?php

namespace App\Services;

use GuzzleHttp\Client;

class VaultService
{
    protected $client;
    function getConnectionData($url, $key)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Vault-Token: ' . $key));
        $resultData = curl_exec($ch);
        $dbArr = json_decode($resultData);
        curl_close($ch);
        return $dbArr;
    }
    function getVaultSecret($path)
    {
        $client = new Client();

        // // dd(env('VAULT_ADDR'));
        $response = $client->request('GET', $VAULT_ADDR . '/v1/secret/data/QA_Degrees/QA_Degrees_LMS', [
            'headers' => [
                'X-Vault-Token' => $VAULT_TOKEN,
            ],
            'http_errors' => false,
            'verify' => false,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to retrieve secrets from Vault: ' . $response->getBody());
        }

        $body = json_decode($response->getBody(), true);
        // If using KV v2, the data will be under 'data' and 'data' again
        return $body['data']['data'];
    }
}
