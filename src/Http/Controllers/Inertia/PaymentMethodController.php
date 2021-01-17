<?php

namespace RenokiCo\BillingPortal\Http\Controllers\Inertia;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Redirect;
use RenokiCo\BillingPortal\BillingPortal;

class PaymentMethodController extends Controller
{
    /**
     * Initialize the controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware(function (Request $request, Closure $next) {
            BillingPorta::getBillableFromRequest($request)->createOrGetStripeCustomer();

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        BillingPorta::getBillableFromRequest($request)->updateDefaultPaymentMethodFromStripe();

        $defaultPaymentMethod = BillingPorta::getBillableFromRequest($request)->defaultPaymentMethod();

        $methods = BillingPorta::getBillableFromRequest($request)
            ->paymentMethods()
            ->filter(function ($method) {
                return $method->type === 'card';
            })->map(function ($method) use ($defaultPaymentMethod) {
                return [
                    'default' => $method->id === optional($defaultPaymentMethod)->id,
                    'id' => $method->id,
                    'brand' => $method->card->brand,
                    'last_four' => $method->card->last4,
                    'month' => $method->card->exp_month,
                    'year' => $method->card->exp_year,
                ];
            });

        return Inertia::render('BillingPortal/PaymentMethod/Index', [
            'methods' => $methods,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('BillingPortal/PaymentMethod/Create', [
            'intent' => BillingPorta::getBillableFromRequest($request)->createSetupIntent(),
            'stripe_key' => config('cashier.key'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        BillingPorta::getBillableFromRequest($request)->addPaymentMethod($request->token);

        if (! BillingPorta::getBillableFromRequest($request)->hasDefaultPaymentMethod()) {
            BillingPorta::getBillableFromRequest($request)->updateDefaultPaymentMethod($request->token);
        }

        return Redirect::route('billing-portal.payment-method.index')
            ->with('success', 'The new payment method got added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $paymentMethod)
    {
        try {
            $paymentMethod = BillingPorta::getBillableFromRequest($request)->findPaymentMethod($paymentMethod);
        } catch (Exception $e) {
            return Redirect::route('billing-portal.payment-method.index')
                ->with('success', 'The payment method got removed!');
        }

        if ($paymentMethod) {
            $paymentMethod->delete();
        }

        return Redirect::route('billing-portal.payment-method.index')
            ->with('success', 'The payment method got removed!');
    }

    /**
     * Set the default payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $paymentMethod
     * @return \Illuminate\Http\
     */
    public function setDefault(Request $request, string $paymentMethod)
    {
        try {
            BillingPorta::getBillableFromRequest($request)->updateDefaultPaymentMethod($paymentMethod);
        } catch (Exception $e) {
            return Redirect::route('billing-portal.payment-method.index')
                ->with('success', 'The default payment method got updated!');
        }

        return Redirect::route('billing-portal.payment-method.index')
            ->with('success', 'The default payment method got updated!');
    }
}
