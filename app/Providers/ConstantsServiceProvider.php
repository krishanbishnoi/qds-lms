<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConstantsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load global constants
        $constants = include base_path('app/constants.php');

        foreach ($constants as $key => $value) {
            config([$key => $value]);
        }
    }
}
