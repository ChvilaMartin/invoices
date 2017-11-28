<?php

namespace Pixiucz\Invoices\Artisan;

use Illuminate\Console\Command;

class Migrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->info('Migrating...');

        $migration = new \Pixiucz\Invoices\CreatePixiuInvoicesTable();
        $migration->down();
        $migration->up();

        $this->info('Migration completed');
    }
}
