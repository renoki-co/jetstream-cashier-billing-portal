<?php

namespace RenokiCo\BillingPortal\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Laravel\Jetstream\Console\InstallCommand as JetstreamInstallCommand;

class InstallCommand extends JetstreamInstallCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing-portal:install
        {stack : The development stack that should be installed}
        {cashier=stripe : The Cashier stack that should be installed}
        {--composer=global : Absolute path to the Composer binary which should be used to install packages}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Jetstream Cashier Billing Portal components and resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->installCashierRegisterStack();

        $this->installInertiaStack();

        $this->callSilent('vendor:publish', ['--provider' => 'RenokiCo\BillingPortal\BillingPortalServiceProvider', '--tag' => 'provider', '--force' => true]);

        $this->installServiceProviderAfter('CashierRegisterServiceProvider', 'BillingPortalServiceProvider');

        if ($this->argument('cashier') === 'stripe') {
            $this->installStripeStack();
        }
    }

    /**
     * Install the Cashier Register stack into the application.
     *
     * @return void
     */
    protected function installCashierRegisterStack()
    {
        if ($this->argument('cashier') === 'stripe') {
            $this->requireComposerPackages('laravel/cashier:^13.4');
        }

        $this->callSilent('vendor:publish', ['--provider' => 'RenokiCo\CashierRegister\CashierRegisterServiceProvider', '--tag' => 'config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--provider' => 'RenokiCo\CashierRegister\CashierRegisterServiceProvider', '--tag' => 'migrations', '--force' => true]);
        $this->callSilent('vendor:publish', ['--provider' => 'RenokiCo\CashierRegister\CashierRegisterServiceProvider', '--tag' => 'provider', '--force' => true]);

        $this->installServiceProviderAfter('JetstreamServiceProvider', 'CashierRegisterServiceProvider');
    }

    /**
     * Install the Inertia stack into the application.
     *
     * @return void
     */
    protected function installInertiaStack()
    {
        $this->callSilent('vendor:publish', ['--provider' => 'RenokiCo\BillingPortal\BillingPortalServiceProvider', '--tag' => 'config', '--force' => true]);

        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/BillingPortal'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/BillingPortal'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/inertia/resources/js/Pages/BillingPortal', resource_path('js/Pages/BillingPortal'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/inertia/resources/js/BillingPortal', resource_path('js/BillingPortal'));

        copy(__DIR__.'/../../../stubs/inertia/resources/js/Layouts/BillingPortalLayout.vue', resource_path('js/Layouts/BillingPortalLayout.vue'));

        $this->line('');
        $this->info('Inertia scaffolding for Cashier Billing Portal installed successfully.');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }

    /**
     * Install the Stripe stack into the application.
     *
     * @return void
     */
    protected function installStripeStack()
    {
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/BillingPortal'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/stripe/app/Actions/BillingPortal', app_path('Actions/BillingPortal'));
    }
}
