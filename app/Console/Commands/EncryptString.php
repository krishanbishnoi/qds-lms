<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\EncryptionHelper;

class EncryptString extends Command
{
    protected $signature = 'encrypt:string {string}';
    protected $description = 'Encrypt a given string';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $string = $this->argument('string');
        $encryptedString = EncryptionHelper::encrypt($string);
        $this->info($encryptedString);
    }
}
