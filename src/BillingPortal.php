<?php

namespace RenokiCo\BillingPortal;

class BillingPortal
{
    use Concerns\ResolvesActions;
    use Concerns\ResolvesBillable;

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
}
