<?php

namespace RenokiCo\BillingPortal;

use Closure;
use Illuminate\Database\Eloquent\Model;

class BillingPortal
{
    /**
     * The callback to sync quotas for an user.
     *
     * @var Closure|null
     */
    protected static $syncQuotasCallback;

    /**
     * Register a method that will run when the
     * subscription updates, in order to sync the quotas.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function onSyncingQuotas(Closure $callback)
    {
        static::$syncQuotasCallback = $callback;
    }

    /**
     * Run the syncing quotas callback.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  string  $subscription
     * @return void
     */
    public static function syncQuotas(Model $user, string $subscription)
    {
        if (static::$syncQuotasCallback) {
            static::$syncQuotasCallback($user, $subscription);
        }
    }
}
