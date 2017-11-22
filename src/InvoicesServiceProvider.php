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
        $this->app->bind('InvoiceGenerator', function() {
            return new \Pixiucz\Invoices\InvoiceGenerator(new Renderer());
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Pixiucz\Invoices\Artisan\Migrate::class,
                \Pixiucz\Invoices\Artisan\MakePattern::class
            ]);
        }
    }
}
