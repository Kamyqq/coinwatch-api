<?php

namespace App\Listeners;

use App\Events\PriceAlertHit;
use App\Notifications\PriceAlertTriggered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPriceAlertNotification
{
    public function handle(PriceAlertHit $event): void
    {
        $event->alert->user->notify(new PriceAlertTriggered($event->alert));
    }
}
