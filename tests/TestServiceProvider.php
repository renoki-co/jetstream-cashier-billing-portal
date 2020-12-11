<?php

namespace RenokiCo\BillingPortal\Test;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'test');

        Inertia::setRootView('test::app');
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
