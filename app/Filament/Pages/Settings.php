<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Schemas\CompanyForm;
use App\Models\Company;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'Perusahaan';

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Perusahaan';

    public ?array $data = [];

    public function mount(): void
    {
        $company = Company::first();

        if (! $company) {
            $company = Company::create([
                'name' => 'Nama Perusahaan Anda',
            ]);
        }

        $this->form->fill($company->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema)
            ->statePath('data');
    }

    public function save(): void
    {
        $company = Company::firstOrFail();

        $state = $this->form->getState();

        $oldLogoPath = $company->logo_path;

        $company->update($state);
        $company->refresh();

        if (
            filled($oldLogoPath) &&
            $oldLogoPath !== $company->logo_path
        ) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        $this->form->fill($company->toArray());

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }

    public function cancel(): void
    {
        $company = Company::firstOrFail();

        $this->form->fill($company->toArray());
    }
}
