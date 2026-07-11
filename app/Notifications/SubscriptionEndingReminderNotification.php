<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionEndingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [60, 300, 900];

    public function __construct(public Subscription $subscription)
    {
        $this->afterCommit();
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subscription = $this->subscription->loadMissing('plan', 'tenant');
        $endsAt = $subscription->current_period_ends_at ?? $subscription->trial_ends_at;

        return (new MailMessage)
            ->subject('Your Spaio access renews soon')
            ->view('emails.subscription-ending-reminder', [
                'subscription' => $subscription,
                'user' => $notifiable,
                'endsAt' => $endsAt,
                'billingUrl' => route('panel.billing'),
            ]);
    }
}
