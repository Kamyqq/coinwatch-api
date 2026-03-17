<?php

namespace App\Jobs;

use App\Models\PriceAlert;
use App\Notifications\PriceAlertTriggered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPriceAlertEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public PriceAlert $alert)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->alert->user;

        $user->notify(new PriceAlertTriggered($this->alert));
    }
}
