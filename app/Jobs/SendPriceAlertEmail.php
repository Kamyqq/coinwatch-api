<?php

namespace App\Jobs;

use App\Mail\PriceAlertMail;
use App\Models\PriceAlert;
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

        Mail::to($user)->send(new PriceAlertMail($this->alert));
    }
}
