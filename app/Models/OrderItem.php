<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'name',
        'quantity',
        'unit',
        'unit_price',
        'box_contents',
    ];

    protected $casts = [
        'box_contents' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
