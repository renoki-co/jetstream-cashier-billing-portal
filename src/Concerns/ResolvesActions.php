<?php

namespace RenokiCo\BillingPortal\Concerns;

use RenokiCo\BillingPortal\Contracts\HandleSubscriptions;

trait ResolvesActions
{
    /**
     * Set the class to resolve the subscription actions.
     *
     * @param  string  $class
     * @return void
     */
    public static function handleSubscriptionsUsing(string $class)
    {
        app()->singleton(HandleSubscriptions::class, $class);
    }
}
