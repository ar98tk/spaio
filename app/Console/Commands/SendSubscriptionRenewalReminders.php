<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionEndingReminderNotification;
use Illuminate\Console\Command;

class SendSubscriptionRenewalReminders extends Command
{
    protected $signature = 'subscriptions:send-renewal-reminders';

    protected $description = 'Send Spaio subscription and trial reminder emails three days before access renews or ends.';

    public function handle(): int
    {
        $targetDate = now()->addDays(3)->toDateString();
        $sent = 0;

        Subscription::query()
            ->with('tenant.owner', 'plan')
            ->whereNull('renewal_reminder_sent_at')
            ->whereIn('status', ['trialing', 'active'])
            ->where(function ($query) use ($targetDate): void {
                $query
                    ->whereDate('current_period_ends_at', $targetDate)
                    ->orWhereDate('trial_ends_at', $targetDate);
            })
            ->chunkById(100, function ($subscriptions) use (&$sent): void {
                foreach ($subscriptions as $subscription) {
                    $owner = $subscription->tenant?->owner;

                    if (! $owner) {
                        continue;
                    }

                    $owner->notify(new SubscriptionEndingReminderNotification($subscription));
                    $subscription->forceFill(['renewal_reminder_sent_at' => now()])->save();
                    $sent++;
                }
            });

        $this->info("Queued {$sent} Spaio renewal reminder email(s).");

        return self::SUCCESS;
    }
}
