<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Billing Portal Middleware
    |--------------------------------------------------------------------------
    |
    | Attach middleware to the billing portal pages. Usually, the users
    | are required to be logged in. Since it's built on Jetstream, it's
    | expected to be authenticated with Sanctum & verified.
    |
    */

    'middleware' => [
        'web',
        'auth:sanctum',
        'verified',
        \RenokiCo\BillingPortal\Http\Middleware\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Billing Portal Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix the routes for the billing portal.
    |
    */

    'prefix' => '/billing',

    /*
    |--------------------------------------------------------------------------
    | Webhook Controller
    |--------------------------------------------------------------------------
    |
    | The router settings for the webhook endpoints.
    | This is being prefixed by the prefix key that was configured above.
    |
    */

    'webhooks' => [

        'middleware' => [
            //
        ],

        'stripe' => [

            'path' => '/stripe/webhook',

            'class' => \RenokiCo\BillingPortal\Http\Controllers\StripeWebhook::class,

        ],

    ],
];
