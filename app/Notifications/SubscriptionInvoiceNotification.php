<?php

namespace App\Notifications;

use App\Models\Subscription;
use App\Services\BillingInvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionInvoiceNotification extends Notification implements ShouldQueue
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
        $invoiceService = app(BillingInvoiceService::class);
        $invoice = $invoiceService->payload($this->subscription, $notifiable->email);
        $pdf = $invoiceService->pdf($this->subscription, $invoice);

        return (new MailMessage)
            ->subject('Your Spaio invoice '.$invoice['number'])
            ->view('emails.subscription-invoice', [
                'invoice' => $invoice,
                'subscription' => $this->subscription->loadMissing('plan', 'tenant'),
                'user' => $notifiable,
            ])
            ->attachData($pdf->output(), $invoice['number'].'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
