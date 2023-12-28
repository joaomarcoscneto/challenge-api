<?php

namespace App\Services\Invoices;
use App\Models\Invoice;

class UpdateInvoiceService
{
    public function run(Invoice $invoice, $form)
    {
        $invoice->update($form);

        return $invoice;
    }
}