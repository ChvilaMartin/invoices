<?php namespace Pixiucz\Invoices;

use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        $this->app->make('\Pixiucz\Invoices\InvoiceGenerator');
    }
}
