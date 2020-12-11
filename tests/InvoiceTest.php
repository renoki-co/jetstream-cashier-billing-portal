<?php

namespace RenokiCo\BillingPortal\Test;

use Carbon\Carbon;
use RenokiCo\BillingPortal\Test\Models\User;

class InvoiceTest extends TestCase
{
    public function test_invoices_index()
    {
        $user = factory(User::class)->create();

        $user->subscriptions()->delete();

        $this->actingAs($user)
            ->post(route('billing-portal.subscription.plan-subscribe', ['plan' => static::$stripeFreePlanId]))
            ->assertRedirect(route('billing-portal.subscription.index'));

        $invoices = $user->invoices()->map(function ($invoice) {
            return [
                'description' => $invoice->lines->data[0]->description,
                'created' => Carbon::parse($invoice->created)->diffForHumans(),
                'paid' => $invoice->paid,
                'status' => $invoice->status,
                'url' => $invoice->hosted_invoice_url ?: null,
            ];
        });

        $this->actingAs($user)
            ->get(route('billing-portal.invoice.index'))
            ->assertInertia('BillingPortal/Invoice/Index', [
                'invoices' => $invoices,
            ]);
    }
}
