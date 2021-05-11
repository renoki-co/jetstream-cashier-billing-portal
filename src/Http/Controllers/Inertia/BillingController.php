<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Inertia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use RenokiCo\BillingPortal\BillingPortal;

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
            BillingPortal::getBillable($request)
        );
    }

    /**
     * Get the billing portal redirect response.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @return Illuminate\Routing\Redirector|\Illuminate\Http\Response
     */
    protected function getBillingPortalRedirect(Model $billable)
    {
        $billable->createOrGetStripeCustomer();

        return Inertia::location(
            $billable->billingPortalUrl(route('billing-portal.subscription.index'))
        );
    }
}
