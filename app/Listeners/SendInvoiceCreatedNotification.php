<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Notifications\InvoiceCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvoiceCreatedNotification implements ShouldQueue
{
    public function handle(InvoiceCreated $event)
    {
        $invoice = $event->invoice;

        \Log::info('Handling InvoiceCreated event for invoice ID: ' . $invoice->id);

        $invoice->user->notify((new InvoiceCreatedNotification($invoice))->onQueue('emails'));
    }
}
