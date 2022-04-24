<?php

namespace Modules\Laralite\Observers;

use Modules\Laralite\Models\Customer\Subscription;

class CustomerSubscriptionObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public function creating(Subscription $subscription)
    {
    }

    public function updating(Subscription $subscription)
    {
    }
}
