<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Services\Invoices\DeleteInvoiceService;
use App\Services\Invoices\StoreInvoiceService;
use App\Services\Invoices\UpdateInvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return response()->json(auth()->user()->invoices);
    }

    public function store(StoreInvoiceRequest $request, StoreInvoiceService $storeInvoiceService)
    {
        $invoice = $storeInvoiceService->run($request->validated());

        return response()->json(['invoice' => $invoice], 201);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return response()->json(['invoice' => $invoice]);
    }

    public function update(Invoice $invoice, UpdateInvoiceRequest $request, UpdateInvoiceService $updateInvoiceService)
    {
        $this->authorize('update', $invoice);

        $updateInvoiceService->run($invoice, $request->validated());

        return response()->json(['invoice' => $invoice]);
    }

    public function destroy(Invoice $invoice, DeleteInvoiceService $deleteInvoiceService)
    {
        $this->authorize('delete', $invoice);

        $deleteInvoiceService->run($invoice);

        return response()->json([], 204);
    }
}
