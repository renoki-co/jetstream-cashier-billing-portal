<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Inertia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = BillingPortal::resolveBillable($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        return Inertia::render('BillingPortal/Subscription/Index', [
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
     * @return \Illuminate\Http\Response
     */
    public function subscribeToPlan(Request $request, string $planId)
    {
        $user = BillingPortal::resolveBillable($request);

        $plan = Saas::getPlan($planId);

        $checkoutOptions = array_merge([
            'success_url' => route('billing-portal.subscription.index', ['success' => "You have successfully subscribed to {$plan->getName()}!"]),
            'cancel_url' => route('billing-portal.subscription.index', ['error' => "The subscription to {$plan->getName()} was cancelled!"]),
        ], BillingPortal::getOptionsForStripeCheckout($request, $user, $plan, $request->subscription));

        $checkout = BillingPortal::mutateCheckout(
            $user->newSubscription($request->subscription, $planId),
            $request, $user, $plan, $request->subscription
        )->checkout($checkoutOptions);

        return view('jetstream-cashier-billing-portal::checkout', [
            'checkout' => $checkout,
            'stripeKey' => config('cashier.key'),
        ]);
    }

    /**
     * Swap the plan to a new one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $newPlanId
     * @return \Illuminate\Http\Response
     */
    public function swapPlan(Request $request, string $newPlanId)
    {
        $plan = Saas::getPlan($newPlanId);

        $billable = BillingPortal::resolveBillable($request);

        $subscription = $this->getCurrentSubscription($billable, $request->subscription);

        if ($plan->getPrice() > 0.00 && ! $billable->defaultPaymentMethod()) {
            return $this->subscribeToPlan($request, $newPlanId);
        }

        if (! $billable->subscribed($subscription->name, $plan->getId())) {
            $hasValidSubscription = $subscription && $subscription->valid();

            $subscription = $hasValidSubscription
                ? $subscription->swap($newPlanId)
                : $billable->newSubscription($request->subscription, $newPlanId)->create(optional($billable->defaultPaymentMethod())->id);
        }

        BillingPortal::syncQuotas($billable, $subscription);

        return Redirect::route('billing-portal.subscription.index')
            ->with('success', "The plan got successfully changed to {$plan->getName()}!");
    }

    /**
     * Resume the current cancelled subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resumeSubscription(Request $request)
    {
        $billable = BillingPortal::resolveBillable($request);

        $subscription = $this->getCurrentSubscription($billable, $request->subscription);

        if ($subscription->onGracePeriod()) {
            $subscription->resume();
        }

        BillingPortal::syncQuotas($billable, $subscription);

        return Redirect::route('billing-portal.subscription.index')
            ->with('success', 'The subscription has been resumed.');
    }

    /**
     * Cancel the current active subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription(Request $request)
    {
        $billable = BillingPortal::resolveBillable($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        if ($subscription->recurring()) {
            $subscription->cancel();
        }

        BillingPortal::syncQuotas($billable, $subscription);

        return Redirect::route('billing-portal.subscription.index')
            ->with('success', 'The current subscription got cancelled!');
    }

    /**
     * Get the current user subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  string  $subscription
     * @return Laravel\Cashier\Subscription|null
     */
    protected function getCurrentSubscription(Model $user, string $subscription)
    {
        return $user->subscription($subscription);
    }
}
