<?php

namespace RenokiCo\BillingPortal\Concerns;

use Closure;
use Illuminate\Http\Request;

trait ResolvesAuthorization
{
    use ResolvesBillable;

    /**
     * The closure that will be called to check
     * the authorization on the request for the billing portal.
     *
     * @var null|Closure
     */
    protected static $authorizationCallback;

    /**
     * Set the closure that checks if the current user
     * can perform the requests for the billing portal.
     *
     * @param  Closure  $callback
     * @return void
     */
    public static function resolveAuthorization(Closure $callback)
    {
        static::$authorizationCallback = $callback;
    }

    /**
     * Specify if the user is authorized to perform. Specify false to throw
     * unauthorization error or a \Illuminate\Http\RedirectResponse instance
     * to redirect the user instead of throwing unauthorization error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public static function isAuthorizedToPerform(Request $request)
    {
        $billable = static::getBillable($request);
        $closure = static::$authorizationCallback;

        return $closure
            ? $closure($billable, $request)
            : $billable && $billable->id == $request->user()->id;
    }
}
