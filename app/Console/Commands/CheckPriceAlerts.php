<?php

namespace App\Console\Commands;

use App\Events\PriceAlertHit;
use App\Models\PriceAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPriceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:check-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all active price alerts and triggers them if conditions are met';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting price alert analysis...');

        PriceAlert::with('cryptocurrency')
            ->where('is_triggered', false)
            ->chunk(500, function ($alerts) {
                foreach ($alerts as $alert) {
                    $crypto = $alert->cryptocurrency;
                    $isHit = false;

                    if ($alert->direction === 'above' && $crypto->price >= $alert->target_price) {
                        $isHit = true;
                    } elseif ($alert->direction === 'below' && $crypto->price <= $alert->target_price) {
                        $isHit = true;
                    }

                    if ($isHit) {
                        DB::transaction(function () use ($alert) {
                            $alert->update(['is_triggered' => true]);

                            PriceAlertHit::dispatch($alert);

                            $this->info("Target price hit for: {$alert->id} ({$alert->cryptocurrency->symbol})");
                        });
                    }
                }
            });
        $this->info('Finished analysis');
    }
}
