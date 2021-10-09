<?php

namespace RenokiCo\BillingPortal\Http\Livewire;

use Illuminate\Http\Request;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use RenokiCo\BillingPortal\BillingPortal;
use RenokiCo\BillingPortal\Contracts\HandleSubscriptions;
use RenokiCo\CashierRegister\Saas;

class PlansSlide extends Component
{
    use InteractsWithBanner;

    /**
     * Render the compoenent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function render(Request $request)
    {
        $billable = BillingPortal::getBillable($request);

        $subscription = $this->getCurrentSubscription($billable, $request->subscription);

        return view('components.plans-slide', [
            'currentPlan' => $subscription ? $subscription->getPlan() : null,
            'hasDefaultPaymentMethod' => $billable->hasDefaultPaymentMethod(),
            'paymentMethods' => $billable->paymentMethods(),
            'plans' => Saas::getPlans(),
            'recurring' => $subscription ? $subscription->recurring() : false,
            'cancelled' => $subscription ? $subscription->cancelled() : false,
            'onGracePeriod' => $subscription ? $subscription->onGracePeriod() : false,
            'endingDate' => $subscription ? optional($subscription->ends_at)->format('d M Y \a\t H:i') : null,
        ]);
    }

    /**
     * Redirect the user to subscribe to the plan.
     *
     * @param string $planId
     * @return \Illuminate\Http\Response
     */
    public function subscribeToPlan(string $planId)
    {
        return redirect()->route('billing-portal.subscription.plan-subscribe', ['plan' => $planId]);
    }

    /**
     * Swap the plan to a new one.
     *
     * @param  \RenokiCo\BillingPortal\Contracts\HandleSubscriptions  $manager
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $newPlanId
     * @return \Illuminate\Http\Response|void
     */
    public function swapPlan(HandleSubscriptions $manager, Request $request, string $newPlanId)
    {
        $newPlan = Saas::getPlan($newPlanId);
        $billable = BillingPortal::getBillable($request);

        if (! $subscription = $this->getCurrentSubscription($billable, $request->subscription)) {
            $this->dangerBanner( "The subscription {$request->subscription} does not exist.");
            return false;
        }

        // If the desired plan has a price and the user has no payment method added to its account,
        // redirect it to the Checkout page to finish the payment info & subscribe.
        if ($newPlan->getPrice() > 0.00 && ! $billable->defaultPaymentMethod()) {
            return redirect()->route('billing-portal.subscription.plan-subscribe', ['plan' => $newPlan->getId()]);
        }

        // Otherwise, check if it is not already subscribed to the new plan and initiate
        // a plan swapping. It also takes proration into account.
        if (! $billable->subscribed($subscription->name, $newPlan->getId())) {
            $hasValidSubscription = $subscription && $subscription->valid();

            $subscription = value(function () use ($hasValidSubscription, $subscription, $newPlan, $request, $billable, $manager) {
                if ($hasValidSubscription) {
                    return $manager->swapToPlan($subscription, $billable, $newPlan, $request);
                }

                // However, this is the only place where a ->create() method is involved. At this point, the user has
                // a default payment method set and we will initialize the subscription in case it is not subscribed
                // to a plan with the given subscription name.
                return $manager->subscribeToPlan(
                    $billable, $newPlan, $request
                );
            });

        }

        $this->banner( "The plan got successfully changed to {$newPlan->getName()}!");
    }

    /**
     * Cancel the current active subscription.
     *
     * @param  \RenokiCo\BillingPortal\Contracts\HandleSubscriptions  $manager
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function cancelSubscription(HandleSubscriptions $manager, Request $request)
    {
        $billable = BillingPortal::getBillable($request);

        if (! $subscription = $this->getCurrentSubscription($billable, $request->subscription)) {
            $this->dangerBanner( "The subscription {$request->subscription} does not exist.");
            return false;
        }

        $manager->cancelSubscription($subscription, $billable, $request);

        $this->banner( "The current subscription got cancelled!");
    }

    /**
     * Resume the current cancelled subscription.
     *
     * @param  \RenokiCo\BillingPortal\Contracts\HandleSubscriptions  $manager
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|void
     */
    public function resumeSubscription(HandleSubscriptions $manager, Request $request)
    {
        $billable = BillingPortal::getBillable($request);

        if (! $subscription = $this->getCurrentSubscription($billable, $request->subscription)) {
            $this->dangerBanner( "The subscription {$request->subscription} does not exist.");
            return false;
        }

        $manager->resumeSubscription($subscription, $billable, $request);

        $this->banner( "The subscription has been resumed.");
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
