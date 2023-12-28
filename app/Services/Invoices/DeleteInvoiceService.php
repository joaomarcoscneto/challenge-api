<?php 

namespace App\Services\Invoices;

class DeleteInvoiceService
{
    public function run($invoice)
    {
        return $invoice->delete();
    }
}