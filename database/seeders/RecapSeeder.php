<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Recap;
use Illuminate\Database\Seeder;

class RecapSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Customer::all() as $customer) {
            Recap::create([
                'customer_id' => $customer->id,
                'early_period' => now()->startOfMonth()->toDateString(),
                'last_period' => now()->endOfMonth()->toDateString(),
            ]);
        }
    }
}
