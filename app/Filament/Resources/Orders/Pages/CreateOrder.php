<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\OrderService;
use Filament\Resources\Pages\CreateRecord;

// Pastikan import class ini

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Order
    {
        return app(OrderService::class)->create($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof Order) {
            app(InvoiceService::class)->generateFromOrder($this->record);
        }
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }
}
