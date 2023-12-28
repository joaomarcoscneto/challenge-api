<?php

namespace App\Services\Invoices;
use App\Events\InvoiceCreated;

class StoreInvoiceService
{
    public function run($form)
    {
        $user = auth()->user();
        $invoice = $user->invoices()->create($form);

        event(new InvoiceCreated($invoice));

        return $invoice;
    }
}