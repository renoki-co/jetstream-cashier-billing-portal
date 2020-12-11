<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\BillingPortal\Http\Controllers\Inertia\BillingController;
use RenokiCo\BillingPortal\Http\Controllers\Inertia\InvoiceController;
use RenokiCo\BillingPortal\Http\Controllers\Inertia\PaymentMethodController;
use RenokiCo\BillingPortal\Http\Controllers\Inertia\SubscriptionController;

Route::group([
    'prefix' => config('billing-portal.prefix'),
    'as' => config('billing-portal.as', 'billing-portal.'),
    'middleware' => config('billing-portal.middleware'),
], function () {
    Route::get('/portal', [BillingController::class, 'portal'])->name('portal');

    Route::post('/subscription/subscribe/{plan}', [SubscriptionController::class, 'subscribeToPlan'])->name('subscription.plan-subscribe');
    Route::post('/subscription/swap/{plan}', [SubscriptionController::class, 'swapPlan'])->name('subscription.plan-swap');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resumeSubscription'])->name('subscription.resume');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');

    Route::post('/payment-method/{payment_method}/set-default', [PaymentMethodController::class, 'setDefault'])->name('payment-method.default');

    Route::resource('invoice', InvoiceController::class)->only('index');
    Route::resource('payment-method', PaymentMethodController::class)->except('update', 'edit');
    Route::resource('subscription', SubscriptionController::class)->only('index');
});
