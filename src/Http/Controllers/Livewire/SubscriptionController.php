<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Redirect;
use RenokiCo\BillingPortal\BillingPortal;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $user = BillingPortal::getBillableFromRequest($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        return view('billing-portal.subscription.index', [
            'currentPlan' => $subscription ? $subscription->getPlan() : null,
            'hasDefaultPaymentMethod' => $user->hasDefaultPaymentMethod(),
            'paymentMethods' => $user->paymentMethods(),
            'plans' => Saas::getPlans(),
            'recurring' => $subscription ? $subscription->recurring() : false,
            'cancelled' => $subscription ? $subscription->cancelled() : false,
            'onGracePeriod' => $subscription ? $subscription->onGracePeriod() : false,
            'endingDate' => $subscription ? optional($subscription->ends_at)->format('d M Y \a\t H:i') : null,
        ]);
    }

    /**
     * Subscribe to the plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $planId
     * @return \Illuminate\Contracts\View\View
     */
    public function subscribeToPlan(Request $request, string $planId)
    {

    }

    /**
     * Swap the plan to a new one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $newPlanId
     * @return \Illuminate\Contracts\View\View
     */
    public function swapPlan(Request $request, string $newPlanId)
    {

    }

    /**
     * Resume the current cancelled subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function resumeSubscription(Request $request)
    {

    }

    /**
     * Cancel the current active subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function cancelSubscription(Request $request)
    {

    }

    /**
     * Get the current user subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  string  $subscription
     * @return \Illuminate\Contracts\View\View\null
     */
    protected function getCurrentSubscription(Model $user, string $subscription)
    {

    }
}
