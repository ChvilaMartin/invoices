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
        /*
            October CMS runs on Laravel 5.1 and doesnt support this method
            For now, you have to create 'pixiu_invoices' table on your own
            (use file from migrations folder for Schema builder)
        */


        // $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        $this->app->bind('InvoiceGenerator', function() {
            return new \Pixiucz\Invoices\InvoiceGenerator();
        });
    }
}
