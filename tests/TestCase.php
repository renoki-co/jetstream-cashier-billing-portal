<?php

namespace RenokiCo\BillingPortal\Test;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use RenokiCo\CashierRegister\Saas;
use Stripe\ApiResource;
use Stripe\Exception\InvalidRequestException;
use Stripe\Plan;
use Stripe\Product;
use Stripe\Stripe;

abstract class TestCase extends Orchestra
{
    protected static $productId;

    protected static $freeProductId;

    protected static $stripePlanId;

    protected static $stripeFreePlanId;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();

        $this->loadLaravelMigrations(['--database' => 'sqlite']);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->withFactories(__DIR__.'/database/factories');

        Saas::plan('Monthly $10', static::$stripePlanId)
            ->price(10, 'USD')
            ->features([
                Saas::feature('Build Minutes', 'build.minutes', 3000),
                Saas::feature('Seats', 'teams', 10)->notResettable(),
            ]);

        Saas::plan('Free Plan', static::$stripeFreePlanId)
            ->features([
                Saas::feature('Build Minutes', 'build.minutes', 10),
                Saas::feature('Seats', 'teams', 5)->notResettable(),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        Stripe::setApiKey(getenv('STRIPE_SECRET') ?: env('STRIPE_SECRET'));

        static::$stripePlanId = 'monthly-10-'.Str::random(10);

        static::$stripeFreePlanId = 'free-'.Str::random(10);

        static::$productId = 'product-1'.Str::random(10);

        static::$freeProductId = 'product-free'.Str::random(10);

        Product::create([
            'id' => static::$productId,
            'name' => 'Laravel Cashier Test Product',
            'type' => 'service',
        ]);

        Product::create([
            'id' => static::$freeProductId,
            'name' => 'Laravel Cashier Test Product',
            'type' => 'service',
        ]);

        Plan::create([
            'id' => static::$stripePlanId,
            'nickname' => 'Monthly $10',
            'currency' => 'USD',
            'interval' => 'month',
            'billing_scheme' => 'per_unit',
            'amount' => 1000,
            'product' => static::$productId,
        ]);

        Plan::create([
            'id' => static::$stripeFreePlanId,
            'nickname' => 'Free',
            'currency' => 'USD',
            'interval' => 'month',
            'billing_scheme' => 'per_unit',
            'amount' => 0,
            'product' => static::$freeProductId,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        static::deleteStripeResource(new Plan(static::$stripePlanId));
        static::deleteStripeResource(new Plan(static::$stripeFreePlanId));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            \Laravel\Cashier\CashierServiceProvider::class,
            \RenokiCo\CashierRegister\CashierRegisterServiceProvider::class,
            \RenokiCo\BillingPortal\BillingPortalServiceProvider::class,
            TestServiceProvider::class,
            \ClaudioDekker\Inertia\InertiaTestingServiceProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'wslxrEFGWY6GfGhvN9L3wH3KSRJQQpBD');
        $app['config']->set('auth.providers.users.model', Models\User::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => __DIR__.'/database.sqlite',
            'prefix'   => '',
        ]);

        $app['config']->set('billing-portal.middleware', ['web']);

        $app['config']->set('jetstream.stack', 'inertia');
    }

    /**
     * Reset the database.
     *
     * @return void
     */
    protected function resetDatabase()
    {
        file_put_contents(__DIR__.'/database.sqlite', null);
    }

    protected static function deleteStripeResource(ApiResource $resource)
    {
        try {
            $resource->delete();
        } catch (InvalidRequestException $e) {
            //
        }
    }
}
