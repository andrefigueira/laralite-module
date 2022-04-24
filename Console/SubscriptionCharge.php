<?php

namespace Modules\Laralite\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Laralite\Models\Customer\Subscription;
use Modules\Laralite\Services\CustomerSubscriptionService;

class SubscriptionCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralite:subscription-charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start charging subscription that have reached there end date';

    /**
     * @var CustomerSubscriptionService
     */
    private $customerSubscriptionService;

    /**
     * Create a new command instance.
     *
     * @param CustomerSubscriptionService $customerSubscriptionService
     */
    public function __construct(CustomerSubscriptionService $customerSubscriptionService)
    {
        $this->customerSubscriptionService = $customerSubscriptionService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Get subscriptions to charge....');
        /** @var Subscription $subscription */
        $subscriptions = Subscription::whereIn(
            'status',
            [Subscription::STATUS_ACTIVE, Subscription::STATUS_EXPIRED, Subscription::STATUS_PAYMENT_DUE]
        )->whereDate('expiry_date', '<=', Carbon::now())->get();

        if (!$subscriptions->count()) {
            $this->info('No expired subscriptions found to charge.');
            return;
        }

        foreach ($subscriptions as $subscription) {
            try {
                $this->customerSubscriptionService->collectionSubscriptionPayment($subscription);
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }
        }
        $this->info('All expired Subscriptions have been processed successfully');
    }
}
