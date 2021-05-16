<?php

namespace RenokiCo\BillingPortal\Contracts;

use Illuminate\Http\Request;
use RenokiCo\CashierRegister\Plan;

interface HandleSubscriptions
{
    /**
     * Mutate the checkout object before redirecting the user to subscribe to a certain plan.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @param  \RenokiCo\CashierRegister\Plan  $plan
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function checkoutOnSubscription($subscription, $billable, Plan $plan, Request $request);

    /**
     * Define the logic to be called when the user requests resuming a subscription.
     *
     * @param  \RenokiCo\CashierRegister\Models\Stripe\Subscription  $subscription
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function resumeSubscription($subscription, $billable, Request $request);

    /**
     * Define the subscriptioncancellation action.
     *
     * @param  \RenokiCo\CashierRegister\Models\Stripe\Subscription  $subscription
     * @param  \Illuminate\Database\Eloquent\Model  $billable
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function cancelSubscription($subscription, $billable, Request $request);
}
