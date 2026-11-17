<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Receipt;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Invoice::all() as $invoice) {
            if ($invoice->receipt) {
                continue;
            }

            Receipt::create([
                'receipt_number' => app(InvoiceService::class)->generateReceiptNumber(),
                'invoice_id' => $invoice->id,
                'receipt_date' => now()->toDateString(),
                'amount' => $invoice->total_amount,
                'amount_in_words' => $invoice->amount_in_words,
                'payment_purpose' => 'Pembayaran invoice '.$invoice->invoice_number,
            ]);
        }
    }
}
