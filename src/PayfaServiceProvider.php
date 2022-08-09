<?php

namespace PayFa\Payfa;

use Illuminate\Support\ServiceProvider;

class PayfaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payfa.php', 'payfa'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/payfa.php' => config_path('payfa.php'),
        ]);
    }
}
