<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeliveryInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command install the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Install started...');
        $this->call('key:generate');
        $this->call('migrate');
        $this->call('db:seed');
        $this->call('delivery:generate-token');
        $this->info('The application completely installed.');
    }
}
