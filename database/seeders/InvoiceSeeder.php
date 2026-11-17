<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Order::with('items', 'customer')->get() as $order) {
            if ($order->invoice) {
                continue;
            }

            $subtotal = $order->items->sum(fn ($i) => $i->quantity * $i->unit_price);

            Invoice::create([
                'invoice_number' => app(InvoiceService::class)->generateInvoiceNumber(),
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'invoice_date' => now()->toDateString(),
                'periode' => now()->format('Y-m'),
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
                'amount_in_words' => 'Total '.$subtotal,
                'status' => 'belum_masuk',
            ]);
        }
    }
}
