<?php

namespace RenokiCo\BillingPortal\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use RenokiCo\BillingPortal\BillingPortal;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authorization = BillingPortal::isAuthorizedToPerform($request);

        if ($authorization instanceof RedirectResponse) {
            return $authorization;
        } else if ($authorization) {
            return $next($request);
        }

        abort(403);
    }
}
