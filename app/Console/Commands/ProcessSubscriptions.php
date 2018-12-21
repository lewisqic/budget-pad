<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Services\CompanySubscriptionService;

class ProcessSubscriptions extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle cancelation requests and charge subscription fees.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        CompanySubscriptionService::processPendingCancelations();
        CompanySubscriptionService::processSubscriptionPayments();
    }
}
