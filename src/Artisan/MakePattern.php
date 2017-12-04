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
    protected $signature = 'invoices:makePattern {name} {pattern}';

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
        $exists = \DB::table('pixiu_invoices')->where('name', $this->argument('name'))->first();

        if (isset($exists)) {
            $this->error('Pattern with this name already exists');
            return;
        }

        if (!$this->isPatternCorrect($this->argument('pattern'))){
            $this->error('Pattern not inserted');
            return;
        }

        \DB::table('pixiu_invoices')->insert([
            'name' => $this->argument('name'),
            'pattern' => $this->argument('pattern'),
            'actual_year' => Carbon::now()->year,
            'invoice_number' => 1
        ]);
        $this->info('Inserted pattern ' . $this->argument('name'));
    }

    private function isPatternCorrect(string $pattern)
    {
        if (!preg_match('{year}', $pattern)){
            $this->error('Missing {year} argument in pattern');
            return false;
        }

        if (!preg_match('{number}', $pattern)){
            $this->error('Missing {number} argument in pattern');
            return false;
        }

        return true;
    }
}
