<?php

namespace Pixiucz\Invoices\Artisan;

use Illuminate\Console\Command;
use Carbon\Carbon;

class MakePattern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:MakePattern {name} {pattern}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '<name> <pattern>: name ex.: \'eShopInvoice\' - pattern ex.: \'invoice-{year}/{number} \'';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DB::table('pixiu_invoices')->insert([
            'name' => $this->argument('name'),
            'pattern' => $this->argument('pattern'),
            'actual_year' => Carbon::now()->year,
            'invoice_number' => 0
        ]);
        $this->info('Inserted pattern ' . $this->argument('name'));
    }
}
