<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RenokiCo\BillingPortal\BillingPortal;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = BillingPortal::getBillable($request)->invoicesIncludingPending()->map(function ($invoice) {
            return (object) [
                'description' => $invoice->lines->data[0]->description,
                'created' => $invoice->created,
                'paid' => $invoice->paid,
                'status' => $invoice->status,
                'url' => $invoice->hosted_invoice_url ?: null,
            ];
        });

        return view('billing-portal.invoice.index', [
            'invoices' => $invoices,
        ]);
    }
}
