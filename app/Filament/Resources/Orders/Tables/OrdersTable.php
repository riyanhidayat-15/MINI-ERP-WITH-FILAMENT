<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\OrderResource;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(fn ($record) => OrderResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('billing_status')
                    ->label('Status Tagih')
                    ->badge()
                    ->getStateUsing(fn ($record): string => $record->billing_status)
                    ->color(fn (string $state): string => match ($state) {
                        'ditagih' => 'success',
                        'belum_ditagih' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ditagih' => 'Ditagih',
                        'belum_ditagih' => 'Belum Ditagih',
                        default => $state,
                    }),

                TextColumn::make('delivery_date')
                    ->label('Tgl Kirim')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->delivery_time
                        ? Carbon::parse($record->delivery_time)->format('H:i')
                        : null)
                    ->color(function ($record) {
                        if ($record->billing_status === 'ditagih') {
                            return null;
                        }

                        $today = now()->startOfDay();
                        $deliveryDate = Carbon::parse($record->delivery_date)->startOfDay();

                        if ($deliveryDate->lt($today)) {
                            return 'danger';
                        }

                        if ($deliveryDate->isToday()) {
                            return 'warning';
                        }

                        if ($deliveryDate->isTomorrow()) {
                            return 'info';
                        }

                        return null;
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewInvoice')
                    ->label('Edit Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->visible(fn ($record): bool => (bool) $record->invoice)
                    ->url(fn ($record) => $record->invoice
                        ? OrderResource::getUrl('edit', ['record' => $record])
                        : null),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
