<?php

namespace App\Listeners;

use App\Events\PriceAlertHit;
use App\Jobs\SendPriceAlertEmail;
use App\Mail\PriceAlertMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPriceAlertNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PriceAlertHit $event): void
    {
        SendPriceAlertEmail::dispatch($event->alert);
    }
}
