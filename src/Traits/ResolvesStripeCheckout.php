<?php

namespace RenokiCo\BillingPortal\Traits;

use Closure;
use Illuminate\Http\Request;
use RenokiCo\CashierRegister\Plan;

trait ResolvesStripeCheckout
{
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
    protected static $stripeCheckoutResolver;

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
    public static function resolveStripeCheckout(Closure $callback)
    {
        static::$stripeCheckoutResolver = $callback;
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
        $closure = static::$stripeCheckoutResolver;

        return $closure
            ? $closure($checkout, $request, $billable, $plan, $subscription)
            : $checkout;
    }
}
