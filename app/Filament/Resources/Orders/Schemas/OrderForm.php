<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Category;
use App\Models\Menu;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('order_type')
                    ->label('Tipe Order')
                    ->options([
                        'menu' => 'Dari Menu',
                        'manual' => 'Manual',
                        'box' => 'Custom Box',
                    ])
                    ->default('menu')
                    ->inline()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set): void {
                        $set('items', []);
                    })
                    ->columnSpan(['default' => 'full', 'lg' => 'full']),

                Section::make('Pilih Menu')
                    ->visible(fn ($get): bool => $get('order_type') === 'menu')
                    ->columns(2)
                    ->columnSpan(['default' => 'full', 'lg' => 'full'])
                    ->schema([
                        TextInput::make('menu_search')
                            ->label('Cari Menu')
                            ->placeholder('Cari menu...')
                            ->live(debounce: 400)
                            ->dehydrated(false),

                        Select::make('menu_category')
                            ->label('Kategori')
                            ->placeholder('Semua kategori')
                            ->options(Category::pluck('name', 'id'))
                            ->live()
                            ->dehydrated(false),

                        View::make('filament.schemas.components.menu-cards')
                            ->columnSpan(['default' => 'full', 'lg' => 'full'])
                            ->viewData(function ($get): array {
                                $search = $get('menu_search');
                                $categoryId = $get('menu_category');

                                $menus = Menu::query()
                                    ->when(filled($search), fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                                    ->when(filled($categoryId), fn ($query) => $query->where('category_id', $categoryId))
                                    ->get();

                                return ['menus' => $menus];
                            }),
                    ]),

                Grid::make(3)
                    ->columnSpan(['default' => 'full', 'lg' => 'full'])
                    ->schema([
                        Section::make('Items Order')
                            ->columnSpan(2)
                            ->disabled(fn ($record) => $record?->isEditable() === false)
                            ->schema([
                                Repeater::make('items')
                                    ->label(fn ($get): string => $get('order_type') === 'menu' ? 'Keranjang' : '')
                                    ->relationship()
                                    ->addable(fn ($get): bool => in_array($get('order_type'), ['manual', 'box'], true))
                                    ->defaultItems(fn ($get): int => in_array($get('order_type'), ['manual', 'box'], true) ? 1 : 0)
                                    ->disabled(fn ($record) => $record?->isEditable() === false)
                                    ->schema([
                                        Hidden::make('menu_id'),

                                        TextInput::make('name')
                                            ->label('Nama Item')
                                            ->required()
                                            ->readOnly(fn ($get): bool => $get('../../order_type') === 'menu')
                                            ->columnSpan(2),

                                        TextInput::make('quantity')
                                            ->label('Qty')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live(),

                                        Select::make('unit')
                                            ->label('Unit')
                                            ->options([
                                                'pcs' => 'PCS',
                                                'kg' => 'KG',
                                                'g' => 'G',
                                                'box' => 'BOX',
                                                'pack' => 'PACK',
                                            ])
                                            ->default(fn ($get): ?string => $get('../../order_type') === 'box' ? 'box' : null)
                                            ->required(),

                                        TextInput::make('unit_price')
                                            ->label('Harga Satuan')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->live(),

                                        Placeholder::make('subtotal')
                                            ->label('Subtotal')
                                            ->content(function ($get): string {
                                                $qty = (float) ($get('quantity') ?? 0);
                                                $price = (float) ($get('unit_price') ?? 0);

                                                return 'Rp '.number_format($qty * $price, 0, ',', '.');
                                            }),

                                        Repeater::make('box_contents')
                                            ->label('Isi Box')
                                            ->hidden(fn ($get): bool => $get('../../order_type') !== 'box')
                                            ->schema([
                                                Select::make('menu_id')
                                                    ->label('Menu')
                                                    ->options(Menu::query()->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, Set $set): void {
                                                        if (filled($state)) {
                                                            $menu = Menu::find($state);

                                                            if ($menu) {
                                                                $set('name', $menu->name);
                                                            }
                                                        }
                                                    })
                                                    ->nullable()
                                                    ->dehydrated(),

                                                TextInput::make('name')
                                                    ->label('Nama')
                                                    ->required(),

                                                TextInput::make('quantity')
                                                    ->label('Qty')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required(),
                                            ])
                                            ->columns(3)
                                            ->reorderable(false)
                                            ->columnSpan(['default' => 'full', 'lg' => 'full']),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('+ Tambah Item')
                                    ->reorderable(false)
                                    ->columnSpan(['default' => 'full', 'lg' => 'full']),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Informasi Order')
                                    ->disabled(fn ($record) => $record?->isEditable() === false)
                                    ->schema([
                                        Select::make('customer_id')
                                            ->label('Customer')
                                            ->relationship('customer', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        TextInput::make('name')
                                            ->label('Nama Order')
                                            ->required(),

                                        DatePicker::make('delivery_date')
                                            ->label('Tanggal Kirim')
                                            ->required(),

                                        Textarea::make('notes')
                                            ->label('Catatan'),
                                    ]),

                                Section::make('Ringkasan')
                                    ->schema([
                                        Placeholder::make('total')
                                            ->label('Total Pembayaran')
                                            ->content(function ($get): string {
                                                $items = $get('items') ?? [];

                                                $total = 0;

                                                foreach ($items as $item) {
                                                    $total += (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0);
                                                }

                                                return 'Rp '.number_format($total, 0, ',', '.');
                                            }),
                                    ]),
                            ]),
                    ]),

                Section::make('Invoice')
                    ->relationship('invoice')
                    ->hidden(fn ($operation) => $operation === 'create')
                    ->disabled(fn ($record) => $record?->isEditable() === false)
                    ->columnSpan(['default' => 'full', 'lg' => 'full'])
                    ->columns(2)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('No. Invoice')
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('invoice_date')
                            ->label('Tanggal Invoice')
                            ->required(),

                        TextInput::make('po_number')
                            ->label('No. PO')
                            ->nullable(),

                        TextInput::make('periode')
                            ->label('Periode')
                            ->required(),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('discount_percentage')
                            ->label('Diskon (%)')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),

                        TextInput::make('discount_amount')
                            ->label('Nominal Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('total_amount')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('amount_in_words')
                            ->label('Terbilang')
                            ->columnSpan(['default' => 'full', 'lg' => 'full']),

                        Section::make('Kwitansi')
                            ->relationship('receipt')
                            ->hidden(fn ($operation) => $operation === 'create')
                            ->disabled(fn ($record) => $record?->isEditable() === false)
                            ->columnSpan(['default' => 'full', 'lg' => 'full'])
                            ->columns(2)
                            ->schema([
                                TextInput::make('receipt_number')
                                    ->label('No. Kwitansi')
                                    ->disabled()
                                    ->dehydrated(),

                                DatePicker::make('receipt_date')
                                    ->label('Tanggal Kwitansi')
                                    ->required(),

                                TextInput::make('amount')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                TextInput::make('amount_in_words')
                                    ->label('Terbilang')
                                    ->columnSpan(['default' => 'full', 'lg' => 'full']),

                                TextInput::make('payment_purpose')
                                    ->label('Untuk Pembayaran')
                                    ->columnSpan(['default' => 'full', 'lg' => 'full']),

                                FileUpload::make('proof_photo_path')
                                    ->label('Foto Bukti Tanda Terima')
                                    ->disk('public')
                                    ->image()
                                    ->directory('receipt-proofs')
                                    ->nullable()
                                    ->columnSpan(['default' => 'full', 'lg' => 'full']),
                            ]),
                    ]),

                Actions::make([
                    Action::make('addMenuToCart')
                        ->extraAttributes(['style' => 'display:none'])
                        ->action(function (array $arguments, Set $set, $get): void {
                            $menuId = $arguments['menuId'] ?? null;
                            $menu = Menu::find($menuId);

                            if (! $menu) {
                                return;
                            }

                            $items = $get('items') ?? [];
                            $foundKey = null;

                            foreach ($items as $key => $item) {
                                if (($item['menu_id'] ?? null) == $menu->id) {
                                    $foundKey = $key;
                                    break;
                                }
                            }

                            if ($foundKey !== null) {
                                $items[$foundKey]['quantity'] = (int) ($items[$foundKey]['quantity'] ?? 0) + 1;
                            } else {
                                $items[(string) Str::uuid()] = [
                                    'menu_id' => $menu->id,
                                    'name' => $menu->name,
                                    'quantity' => 1,
                                    'unit' => 'pcs',
                                    'unit_price' => $menu->price,
                                ];
                            }

                            $set('items', $items);
                        }),
                ])->columnSpan(['default' => 'full', 'lg' => 'full']),
            ]);
    }
}
