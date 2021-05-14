<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Inertia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use RenokiCo\BillingPortal\BillingPortal;

class BillingController extends Controller
{
    /**
     * Redirect the user to the subscriptions page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return Redirect::route('billing-portal.subscription.index');
    }

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
            $billable->billingPortalUrl(route('billing-portal.dashboard'))
        );
    }
}
