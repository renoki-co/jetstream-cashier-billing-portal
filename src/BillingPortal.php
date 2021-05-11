<?php

namespace RenokiCo\BillingPortal;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use RenokiCo\CashierRegister\Plan;

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
     * The closure that will be called to get
     * the right Stripe Checkout parameters to call.
     *
     * @var null|Closure
     */
    protected static $stripeCheckoutOptions;

    /**
     * The closure that will be called to modify
     * the Stripe Checkout flow.
     *
     * @var null|Closure
     */
    protected static $stripeCheckoutInterceptor;

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
    public static function resolveBillable(Closure $callback)
    {
        static::$billableOnRequest = $callback;
    }

    /**
     * Get the billable model from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public static function getBillable(Request $request)
    {
        $closure = static::$billableOnRequest;

        return $closure
            ? $closure($request)
            : $request->user();
    }

    /**
     * Set the Stripe Checkout options computator.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function resolveStripeCheckoutOptions(Closure $callback)
    {
        static::$stripeCheckoutOptions = $callback;
    }

    /**
     * Calculate the options for Stripe Checkout for
     * a specific Billable mode, plan and subscription name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $billable
     * @param  \RenokiCo\CashierRegister\Plan  $plan
     * @param  string  $subscription
     * @return array
     */
    public static function getStripeCheckoutOptions(Request $request, $billable, Plan $plan, string $subscription): array
    {
        $closure = static::$stripeCheckoutOptions;

        return $closure
            ? $closure($request, $billable, $plan, $subscription)
            : [];
    }

    /**
     * Set the Stripe Checkout interceptor.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function onCheckout(Closure $callback)
    {
        static::$stripeCheckoutInterceptor = $callback;
    }

    /**
     * Mutate the Stripe checkout for
     * a specific Billable mode, plan and subscription name.
     *
     * @param  \Laravel\Cashier\SubscriptionBuilder  $checkout
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $billable
     * @param  \RenokiCo\CashierRegister\Plan  $plan
     * @param  string  $subscription
     * @return \Laravel\Cashier\SubscriptionBuilder
     */
    public static function mutateCheckout($checkout, Request $request, $billable, Plan $plan, string $subscription)
    {
        $closure = static::$stripeCheckoutInterceptor;

        return $closure
            ? $closure($checkout, $request, $billable, $plan, $subscription)
            : $checkout;
    }
}
