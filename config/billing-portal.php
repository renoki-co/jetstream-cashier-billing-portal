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
    ],

    /*
    |--------------------------------------------------------------------------
    | Billing Portal Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix the routes for the billing portal.
    |
    */

    'prefix' => '/user/billing',

];
