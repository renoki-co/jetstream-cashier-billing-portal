<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\BillingController;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\InvoiceController;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\PaymentMethodController;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\SubscriptionController;

Route::group([
    'prefix' => config('billing-portal.prefix'),
    'as' => 'billing-portal.',
    'middleware' => config('billing-portal.middleware'),
], function () {
    Route::get('/', [BillingController::class, 'dashboard'])->name('dashboard');
    Route::get('/portal', [BillingController::class, 'portal'])->name('portal');

    Route::get('/subscription/subscribe/{plan}', [SubscriptionController::class, 'redirectWithSubscribeIntent'])->name('subscription.plan-subscribe');

    Route::resource('invoice', InvoiceController::class)->only('index');
    Route::resource('payment-method', PaymentMethodController::class)->only('index', 'create', 'store');
    Route::resource('subscription', SubscriptionController::class)->only('index');
});
