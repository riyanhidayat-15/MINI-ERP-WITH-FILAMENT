<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $menus = [
            ['Makanan', 'Nasi Goreng Spesial', 25000, 'Nasi goreng dengan ayam dan telur.'],
            ['Makanan', 'Mie Goreng Jawa', 22000, 'Mie goreng bumbu kecap.'],
            ['Makanan', 'Ayam Bakar Madu', 30000, 'Ayam bakar dengan saus madu.'],
            ['Makanan', 'Soto Ayam', 20000, 'Soto ayam kuah bening.'],
            ['Makanan', 'Gado-Gado', 18000, 'Sayuran dengan bumbu kacang.'],
            ['Makanan', 'Rendang Daging', 35000, 'Rendang daging sapi.'],
            ['Makanan', 'Ikan Goreng Sambal', 28000, 'Ikan goreng dengan sambal.'],
            ['Makanan', 'Capcay Seafood', 32000, 'Capcay dengan seafood.'],
            ['Minuman', 'Es Teh Manis', 5000, 'Teh manis dingin.'],
            ['Minuman', 'Es Jeruk', 7000, 'Jeruk peras dingin.'],
            ['Minuman', 'Kopi Susu', 12000, 'Kopi susu gula aren.'],
            ['Minuman', 'Air Mineral', 4000, 'Air mineral botol.'],
            ['Minuman', 'Jus Alpukat', 15000, 'Jus alpukat creamy.'],
            ['Minuman', 'Es Cendol', 10000, 'Cendol dengan santan.'],
            ['Snack', 'Risoles Mayo', 8000, 'Risoles isi mayo.'],
            ['Snack', 'Pastel Sayur', 7000, 'Pastel isi sayur.'],
            ['Snack', 'Lumpia Goreng', 9000, 'Lumpia isi rebung.'],
            ['Snack', 'Kroket Kentang', 8000, 'Kroket kentang keju.'],
            ['Snack', 'Tahu Isi', 6000, 'Tahu isi sayuran.'],
            ['Snack', 'Tempe Mendoan', 5000, 'Tempe mendoan hangat.'],
            ['Dessert', 'Puding Cokelat', 12000, 'Puding cokelat susu.'],
            ['Dessert', 'Es Buah', 13000, 'Es buah segar.'],
            ['Dessert', 'Klepon', 8000, 'Klepon gula merah.'],
            ['Dessert', 'Lapis Legit', 15000, 'Lapis legit potong.'],
            ['Dessert', 'Brownies', 14000, 'Brownies cokelat.'],
            ['Paket Nasi Box', 'Nasi Box Ayam Goreng', 35000, 'Nasi, ayam goreng, sambal, lalap.'],
            ['Paket Nasi Box', 'Nasi Box Rendang', 40000, 'Nasi, rendang, telur, sayur.'],
            ['Paket Nasi Box', 'Nasi Box Ikan Bakar', 38000, 'Nasi, ikan bakar, sambal.'],
            ['Paket Nasi Box', 'Nasi Box Soto', 32000, 'Nasi, soto ayam, perkedel.'],
            ['Paket Nasi Box', 'Nasi Box Gado-Gado', 30000, 'Nasi, gado-gado, kerupuk.'],
            ['Paket Nasi Box', 'Nasi Box Ayam Bakar', 36000, 'Nasi, ayam bakar, sambal.'],
            ['Paket Nasi Box', 'Nasi Box Capcay', 34000, 'Nasi, capcay, ayam.'],
            ['Paket Nasi Box', 'Nasi Box Ikan Goreng', 37000, 'Nasi, ikan goreng, sambal.'],
        ];

        foreach ($menus as [$categoryName, $name, $price, $description]) {
            $categoryId = $categories[$categoryName] ?? null;

            if (! $categoryId) {
                continue;
            }

            Menu::firstOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryId,
                    'price' => $price,
                    'description' => $description,
                    'image_url' => null,
                ]
            );
        }
    }
}
