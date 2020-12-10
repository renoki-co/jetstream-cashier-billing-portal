<?php

namespace RenokiCo\BillingPortal;

use Illuminate\Support\ServiceProvider;

class BillingPortalServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/billing-portal.php' => config_path('billing-portal.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/billing-portal.php', 'billing-portal'
        );

        $this->loadRoutesFrom(__DIR__.'/../routes/'.config('jetstream.stack').'.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InstallCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
