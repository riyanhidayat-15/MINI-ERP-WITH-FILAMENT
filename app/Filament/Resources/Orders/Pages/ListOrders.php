<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'belum_ditagih' => Tab::make('Belum Ditagih')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('invoice.receipt', fn (Builder $q) => $q->whereNotNull('proof_photo_path')))
                ->badge(Order::query()->whereDoesntHave('invoice.receipt', fn (Builder $q) => $q->whereNotNull('proof_photo_path'))->count())
                ->badgeColor('warning'),

            'ditagih' => Tab::make('Ditagih')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('invoice.receipt', fn (Builder $q) => $q->whereNotNull('proof_photo_path'))),

            'semua' => Tab::make('Semua Orderan'),
        ];
    }
}
