<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'PT Contoh', 'city' => 'Jakarta', 'province' => 'DKI Jakarta', 'postal_code' => '10110', 'address' => 'Jl. A', 'phone' => '08111'],
            ['name' => 'CV Sample', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'postal_code' => '40111', 'address' => 'Jl. B', 'phone' => '08222'],
        ];

        foreach ($customers as $data) {
            Customer::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
