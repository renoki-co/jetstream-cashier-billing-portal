<?php

namespace RenokiCo\BillingPortal;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BillingPortal
{
    /**
     * The callback to sync quotas for an user.
     *
     * @var Closure|null
     */
    protected static $syncQuotasCallback;

    /**
     * The closure that will be called to retrieve
     * the billable model on a specific request.
     *
     * @var null|Closure
     */
    protected static $billableOnRequest;

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
     * @param  \Illuminate\Database\Eloquent\Model  $subscription
     * @return void
     */
    public static function syncQuotas(Model $user, Model $subscription)
    {
        if (static::$syncQuotasCallback) {
            $callback = static::$syncQuotasCallback;

            $callback($user, $subscription);
        }
    }

    /**
     * Set the closure that returns the billable model
     * by passing a specific request to it.
     *
     * @param  Closure  $callback
     * @return void
     */
    public static function setBillableOnRequest(Closure $callback)
    {
        static::$billableOnRequest = $callback;
    }

    /**
     * Get the billable model from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public static function getBillableFromRequest(Request $request)
    {
        return static::$billableOnRequest
            ? static::$billableOnRequest($request)
            : $request->user();
    }
}
