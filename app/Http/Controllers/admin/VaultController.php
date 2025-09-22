<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Services\VaultService;
use GuzzleHttp\Client;

class VaultController extends BaseController
{
    protected $vault;

    public function __construct(VaultService $vault)
    {
        $this->vault = $vault;
    }

    public function showSecret()
    {
        // Access the username and password For database
        $dbCredentials = $this->vault->getVaultSecret('secret/data/db_credentials');
        $dbDatabase = $dbCredentials['database'];
        $dbUsername = $dbCredentials['username'];
        $dbPassword = $dbCredentials['password'];
        // Access the username and password For mail
        $mailCredentials = $this->vault->getVaultSecret('secret/data/mail_credentials');
        $mailUsername = $mailCredentials['username'];
        $mailPassword = $mailCredentials['password'];
        dd($dbDatabase, $dbUsername, $dbPassword , $mailUsername, $mailPassword);
    }
}