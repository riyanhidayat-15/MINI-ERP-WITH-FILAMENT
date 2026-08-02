<?php

namespace App\Filament\Resources\BranchStocks\Pages;

use App\Filament\Resources\BranchStocks\BranchStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBranchStocks extends ManageRecords
{
    protected static string $resource = BranchStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
