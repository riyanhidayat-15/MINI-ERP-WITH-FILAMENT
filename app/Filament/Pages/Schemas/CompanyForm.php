<?php

namespace App\Filament\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas perusahaan')
                ->description('Informasi utama yang digunakan pada invoice dan dokumen perusahaan.')
                ->icon('heroicon-o-building-office-2')
                ->columns(2)
                ->schema([
                    FileUpload::make('logo_path')
                        ->label('Logo perusahaan')
                        ->image()
                        ->disk('public')
                        ->directory('logos')
                        ->visibility('public')
                        ->imageEditor()
                        ->maxSize(2048)
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('Nama perusahaan')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Nomor telepon')
                        ->tel()
                        ->maxLength(50),

                    Textarea::make('address')
                        ->label('Alamat perusahaan')
                        ->rows(3)
                        ->columnSpanFull(),

                    TextInput::make('city')
                        ->label('Kota')
                        ->maxLength(100),

                    TextInput::make('province')
                        ->label('Provinsi')
                        ->maxLength(100),

                    TextInput::make('director_name')
                        ->label('Nama direktur')
                        ->maxLength(255),
                ]),

            Section::make('Informasi pembayaran')
                ->description('Rekening yang dicantumkan pada invoice dan kwitansi.')
                ->icon('heroicon-o-banknotes')
                ->columns(2)
                ->schema([
                    TextInput::make('bank_name')
                        ->label('Nama bank')
                        ->maxLength(100),

                    TextInput::make('bank_account_name')
                        ->label('Nama pemilik rekening')
                        ->maxLength(255),

                    TextInput::make('bank_account_number')
                        ->label('Nomor rekening')
                        ->maxLength(100)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
