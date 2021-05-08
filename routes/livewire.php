<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\BillingController;
use RenokiCo\BillingPortal\Http\Controllers\Livewire\SubscriptionController;

Route::group([
    'prefix' => config('billing-portal.prefix'),
    'as' => config('billing-portal.as', 'billing-portal.'),
    'middleware' => config('billing-portal.middleware'),
], function () {
    Route::get('/portal', [BillingController::class, 'portal'])->name('portal');

    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
});
