<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $order = Order::firstOrCreate(
                ['order_number' => 'ORD-'.$customer->id],
                [
                    'customer_id' => $customer->id,
                    'name' => 'Order '.$customer->name,
                    'delivery_date' => now()->addDays(3)->toDateString(),
                    'delivery_time' => '10:00',
                    'status' => 'process',
                    'notes' => 'Seed order',
                ]
            );

            if (! $order->wasRecentlyCreated) {
                continue;
            }

            OrderItem::create([
                'order_id' => $order->id,
                'name' => 'Item A',
                'quantity' => 2,
                'unit' => 'pcs',
                'unit_price' => 10000,
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'name' => 'Item B',
                'quantity' => 1,
                'unit' => 'box',
                'unit_price' => 25000,
            ]);
        }
    }
}
