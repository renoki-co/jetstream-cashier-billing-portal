<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Inertia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Redirect;

class BillingController extends Controller
{
    /**
     * Redirect to the Stripe portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function portal(Request $request)
    {
        return $this->getBillingPortalRedirect(
            $request->user(), false
        );
    }

    /**
     * Get the billing portal redirect response.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  bool  $asResponse
     * @return Illuminate\Routing\Redirector|\Illuminate\Http\Response
     */
    protected function getBillingPortalRedirect(Model $user, bool $asResponse = true)
    {
        $user->createOrGetStripeCustomer();

        $url = $user->billingPortalUrl(route('billing-portal.subscription.index'));

        return $asResponse
            ? response('', 409)->header('X-Inertia-Location', $url)
            : Redirect::to($url);
    }
}
