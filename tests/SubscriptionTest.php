<?php

namespace RenokiCo\BillingPortal\Test;

use RenokiCo\BillingPortal\Test\Models\User;
use RenokiCo\CashierRegister\Saas;

class SubscriptionTest extends TestCase
{
    public function test_index_subscriptions()
    {
        $user = factory(User::class)->create();

        $subscription = $user->subscription('main');

        $this->actingAs($user)
            ->get(route('billing-portal.subscription.index'))
            ->assertInertia('BillingPortal/Subscription/Index', [
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

    public function test_subscribe_to_free_plan()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripeFreePlanId]))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $this->assertCount(1, $user->subscriptions);
    }

    public function test_subscribe_to_paid_plan_without_payment_method()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripePlanId]))
            ->assertRedirect(route('billing-portal.payment-method.index'));

        $this->assertCount(0, $user->subscriptions);
    }

    public function test_swap_to_free_plan()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $user->newSubscription('main', static::$stripePlanId)->create('pm_card_visa');

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-swap', ['plan' => static::$stripeFreePlanId]))
            ->assertRedirect(route('billing-portal.subscription.index'));
    }

    public function test_swap_to_paid_plan_without_payment_method()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripeFreePlanId]))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-swap', ['plan' => static::$stripePlanId]))
            ->assertRedirect(route('billing-portal.payment-method.index'));
    }

    public function test_cancel_and_resume_plan()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripeFreePlanId]))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.cancel'))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $this->assertTrue($user->subscription('main')->cancelled());

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.resume'))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $this->assertFalse($user->subscription('main')->cancelled());
    }
}
