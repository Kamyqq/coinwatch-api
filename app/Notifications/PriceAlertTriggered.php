<?php

namespace App\Notifications;

use App\Models\PriceAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceAlertTriggered extends Notification
{
    use Queueable;

    public function __construct(public PriceAlert $alert)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Price Alert for {$this->alert->cryptocurrency->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your alert for {$this->alert->cryptocurrency->name} has been triggered.")
            ->line("Target Price: {$this->alert->target_price} PLN")
            ->line("Current Price: {$this->alert->cryptocurrency->price} PLN")
            ->action('Check your alerts', url('/'))
            ->line('Thank you for using CoinWatch!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'crypto_symbol' => $this->alert->cryptocurrency->symbol,
            'message' => "Target price {$this->alert->target_price} PLN was hit!}"
        ];
    }
}
