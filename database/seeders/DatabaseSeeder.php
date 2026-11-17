<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            ReceiptSeeder::class,
            RecapSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
        ]);
    }
}
