<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(function (): bool {
                    $record = $this->getRecord();

                    if (! $record instanceof Order) {
                        return false;
                    }

                    $invoice = $record->invoice;

                    return $invoice instanceof Invoice && (bool) $invoice->receipt;
                })
                ->action(function (): void {
                    $record = $this->getRecord();

                    if (! $record instanceof Order) {
                        return;
                    }

                    $missing = $this->missingPdfFields($record);

                    if (! empty($missing)) {
                        Notification::make()
                            ->title('Belum bisa download PDF')
                            ->body('Field berikut masih kosong: '.implode(', ', $missing))
                            ->danger()
                            ->send();

                        return;
                    }

                    $invoice = $record->invoice;

                    if (! $invoice instanceof Invoice) {
                        return;
                    }

                    $this->js("window.open('".route('invoice.pdf', $invoice)."', '_blank')");
                }),
            DeleteAction::make(),
        ];
    }

    protected function missingPdfFields(Order $record): array
    {
        $missing = [];

        if (! $record->customer_id) {
            $missing[] = 'Customer';
        }
        if (blank($record->name)) {
            $missing[] = 'Nama Order';
        }
        if (blank($record->delivery_date)) {
            $missing[] = 'Tanggal Kirim';
        }
        if ($record->items->isEmpty()) {
            $missing[] = 'Items Order';
        } else {
            foreach ($record->items as $item) {
                if (blank($item->name)) { $missing[] = 'Nama Item'; break; }
                if (blank($item->quantity)) { $missing[] = 'Qty'; break; }
                if (blank($item->unit)) { $missing[] = 'Unit'; break; }
                if (blank($item->unit_price)) { $missing[] = 'Harga Satuan'; break; }
            }
        }

        $invoice = $record->invoice;
        if ($invoice instanceof Invoice) {
            if (blank($invoice->po_number)) { $missing[] = 'No. PO'; }
            if (blank($invoice->invoice_date)) { $missing[] = 'Tanggal Invoice'; }
            if (blank($invoice->periode)) { $missing[] = 'Periode'; }
            if (blank($invoice->subtotal)) { $missing[] = 'Subtotal'; }
            if (blank($invoice->total_amount)) { $missing[] = 'Total'; }
            if (blank($invoice->amount_in_words)) { $missing[] = 'Terbilang Invoice'; }

            $receipt = $invoice->receipt;
            if ($receipt) {
                if (blank($receipt->receipt_date)) { $missing[] = 'Tanggal Kwitansi'; }
                if (blank($receipt->amount)) { $missing[] = 'Jumlah Kwitansi'; }
                if (blank($receipt->amount_in_words)) { $missing[] = 'Terbilang Kwitansi'; }
                if (blank($receipt->payment_purpose)) { $missing[] = 'Untuk Pembayaran'; }
            }
        }

        return array_values(array_unique($missing));
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }
}
