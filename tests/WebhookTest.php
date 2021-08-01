<?php

namespace RenokiCo\BillingPortal\Test;

use RenokiCo\BillingPortal\Test\Models\User;

class WebhookTest extends TestCase
{
    public function text_webhook_for_invoice_payment_succeeded()
    {
        $user = factory(User::class)->create();

        $subscription = $user->subscription('main');

        $this->postJson(route('billing-portal.stripe.webhook'), [
            'id' => 'foo',
            'type' => 'invoice.payment_succeeded',
            'data' => [
                'object' => [
                    'customer' => $user->stripe_id,
                    'subscription' => $subscription->stripe_id,
                ],
            ],
        ])->assertOk();
    }
}
