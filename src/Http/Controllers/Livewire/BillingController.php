<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use RenokiCo\BillingPortal\BillingPortal;

class BillingController extends Controller
{
    /**
     * Redirect to the Stripe portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function portal(Request $request)
    {
        return $this->getBillingPortalRedirect(
            BillingPortal::getBillableFromRequest($request)
        );
    }

    /**
     * Get the billing portal redirect response.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function getBillingPortalRedirect(Model $user)
    {
        $user->createOrGetStripeCustomer();

        return Redirect::intended(
            $user->billingPortalUrl(route('billing-portal.subscription.index'))
        );
    }
}
