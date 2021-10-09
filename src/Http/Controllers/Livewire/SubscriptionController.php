<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RenokiCo\BillingPortal\BillingPortal;
use RenokiCo\BillingPortal\Contracts\HandleSubscriptions;
use RenokiCo\CashierRegister\Saas;

class SubscriptionController extends Controller
{
    /**
     * Initialize the controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $request->merge([
            'subscription' => $request->subscription ?: 'main',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('billing-portal.subscription.index');
    }

    /**
     * Redirect the user to subscribe to the plan.
     *
     * @param  \RenokiCo\BillingPortal\Contracts\HandleSubscriptions  $manager
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $planId
     * @return \Illuminate\Http\Response
     */
    public function redirectWithSubscribeIntent(HandleSubscriptions $manager, Request $request, string $planId)
    {
        $billable = BillingPortal::getBillable($request);

        $plan = Saas::getPlan($planId);

        $subscription = $billable->newSubscription($request->subscription, $plan->getId());

        $checkout = $manager->checkoutOnSubscription(
            $subscription, $billable, $plan, $request
        );

        return view('jetstream-cashier-billing-portal::checkout', [
            'checkout' => $checkout,
            'stripeKey' => config('cashier.key'),
        ]);
    }

    /**
     * Get the current billable subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @param  string  $subscription
     * @return \Laravel\Cashier\Subscription|null
     */
    protected function getCurrentSubscription($billable, string $subscription)
    {
        return $billable->subscription($subscription);
    }
}
