<?php

namespace RenokiCo\BillingPortal\Test;

use RenokiCo\BillingPortal\Test\Models\User;

class BillingPortalTest extends TestCase
{
    public function test_billing_redirect_to_portal()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripeFreePlanId]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('billing-portal.portal'))
            ->assertStatus(409);
    }
}
