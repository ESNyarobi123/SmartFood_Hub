<?php

namespace App\Console\Commands;

use App\Models\Food\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire food subscriptions whose end date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expired = Subscription::whereIn('status', ['active', 'paused'])
            ->where('end_date', '<', now()->startOfDay())
            ->update([
                'status' => 'expired',
                'expired_at' => now(),
            ]);

        $this->info("Expired {$expired} subscription(s).");

        return self::SUCCESS;
    }
}
