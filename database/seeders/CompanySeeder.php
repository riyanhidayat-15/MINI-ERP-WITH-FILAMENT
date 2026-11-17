<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::updateOrCreate(
            ['name' => 'Catering SaaS'],
            [
                'address' => 'Jl. Contoh No. 1',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'phone' => '021-000000',
                'director_name' => 'Direktur',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Catering SaaS',
            ]
        );
    }
}
