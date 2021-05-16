<?php

namespace App\Providers;

use App\Actions\BillingPortal\HandleSubscriptions;
use RenokiCo\BillingPortal\BillingPortal;
use RenokiCo\BillingPortal\BillingPortalServiceProvider as BaseProvider;

class BillingPortalServiceProvider extends BaseProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // BillingPortal::dontProrateOnSwap();

        BillingPortal::handleSubscriptionsUsing(HandleSubscriptions::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }
}
