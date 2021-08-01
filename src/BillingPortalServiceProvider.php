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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/billing-portal.php' => config_path('billing-portal.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../stubs/BillingPortalServiceProvider.php' => app_path('Providers/BillingPortalServiceProvider.php'),
            ], 'provider');

            $this->commands([
                Console\Commands\InstallCommand::class,
            ]);
        }

        $this->mergeConfigFrom(
            __DIR__.'/../config/billing-portal.php', 'billing-portal'
        );

        $this->loadRoutesFrom(__DIR__.'/../routes/'.config('jetstream.stack').'.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/webhooks.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'jetstream-cashier-billing-portal');
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
