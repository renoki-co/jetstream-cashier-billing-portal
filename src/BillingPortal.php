<?php

namespace RenokiCo\BillingPortal;

use Closure;
use Illuminate\Http\Request;

class BillingPortal
{
    use Traits\ResolvesQuotas;
    use Traits\ResolvesStripeCheckout;

    /**
     * The closure that will be called to retrieve
     * the billable model on a specific request.
     *
     * @var null|Closure
     */
    protected static $billable;

    /**
     * Wether the proration should occur when swapping between plans.
     *
     * @var bool
     */
    protected static $proratesOnSwap = true;

    /**
     * Don't prorate on swapping.
     *
     * @return void
     */
    public static function dontProrateOnSwap()
    {
        static::$proratesOnSwap = false;
    }

    /**
     * Wether the proration should occur when swapping.
     *
     * @return bool
     */
    public static function proratesOnSwap(): bool
    {
        return static::$proratesOnSwap;
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
        static::$billable = $callback;
    }

    /**
     * Get the billable model from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public static function getBillable(Request $request)
    {
        $closure = static::$billable;

        return $closure
            ? $closure($request)
            : $request->user();
    }
}
