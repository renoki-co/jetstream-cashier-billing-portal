<?php

namespace RenokiCo\BillingPortal\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait ResolvesQuotas
{
    /**
     * The callback to sync quotas for an user.
     *
     * @var Closure|null
     */
    protected static $syncQuotasResolver;

    /**
     * Register a method that will run when the
     * subscription updates, in order to sync the quotas.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function resolveQuotasSync(Closure $callback)
    {
        static::$syncQuotasResolver = $callback;
    }

    /**
     * Run the syncing quotas callback.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @param  \Illuminate\Database\Eloquent\Model  $subscription
     * @return void
     */
    public static function syncQuotas(Model $billable, Model $subscription)
    {
        if (static::$syncQuotasResolver) {
            $callback = static::$syncQuotasResolver;

            $callback($billable, $subscription);
        }
    }
}
