<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification
{
    use Queueable;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        \Log::info('Sending email notification for invoice ID: ' . $this->invoice->id);
    
        return (new MailMessage)
            ->subject('Invoice Created')
            ->line('Your invoice '. $this->invoice->number .' has been created.');
    }

    public function toArray($notifiable)
    {
        return [
        ];
    }
}