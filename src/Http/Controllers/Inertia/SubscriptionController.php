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
        $user = BillingPorta::getBillableFromRequest($request);

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
        $user = BillingPorta::getBillableFromRequest($request);

        $plan = Saas::getPlan($planId);

        if ($plan->getPrice() > 0.00 && ! $user->defaultPaymentMethod()) {
            return $this->redirectToAddPaymentMethod();
        }

        $payment = $plan->getPrice() <= 0.00
            ? null
            : $user->defaultPaymentMethod()->id;

        $subscription = $user->newSubscription($request->subscription, $planId)->create($payment);

        $this->syncQuotas(BillingPorta::getBillableFromRequest($request), $subscription);

        return Redirect::route('billing-portal.subscription.index')
            ->with('success', "You have successfully subscribed to {$plan->getName()}!");
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

        $user = BillingPorta::getBillableFromRequest($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        if ($plan->getPrice() > 0.00 && ! $user->defaultPaymentMethod()) {
            return $this->redirectToAddPaymentMethod();
        }

        if (! $user->subscribed($subscription->name, $plan->getId())) {
            $hasValidSubscription = $subscription && $subscription->valid();

            $subscription = $hasValidSubscription
                ? $subscription->swap($newPlanId)
                : $user->newSubscription($request->subscription, $newPlanId)->create(optional($user->defaultPaymentMethod())->id);
        }

        $this->syncQuotas(BillingPorta::getBillableFromRequest($request), $subscription);

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
        $user = BillingPorta::getBillableFromRequest($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        if ($subscription->onGracePeriod()) {
            $subscription->resume();
        }

        $this->syncQuotas(BillingPorta::getBillableFromRequest($request), $subscription);

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
        $user = BillingPorta::getBillableFromRequest($request);

        $subscription = $this->getCurrentSubscription($user, $request->subscription);

        if ($subscription->recurring()) {
            $subscription->cancel();
        }

        $this->syncQuotas(BillingPorta::getBillableFromRequest($request), $subscription);

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

    /**
     * Redirect to add a payment method.
     *
     * @return \Illuminate\Routing\Redirector
     */
    protected function redirectToAddPaymentMethod()
    {
        return Redirect::route('billing-portal.payment-method.index')
            ->with('success', 'Please add or set a default payment method before subscribing to a paid plan.');
    }

    /**
     * Sync all available quotas.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  string  $subscription
     * @return void
     */
    protected function syncQuotas(Model $user, $subscription)
    {
        BillingPortal::syncQuotas($user, $subscription);
    }
}
