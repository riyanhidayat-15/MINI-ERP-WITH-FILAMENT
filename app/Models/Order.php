<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_type',
        'order_number',
        'customer_id',
        'name',
        'delivery_date',
        'delivery_time',
        'status',
        'notes',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function isEditable(): bool
    {
        return ! ($this->invoice?->receipt?->proof_photo_path);
    }

    public function getBillingStatusAttribute(): string
    {
        return ($this->invoice?->receipt?->proof_photo_path) ? 'ditagih' : 'belum_ditagih';
    }

    public function scopeBilled($query)
    {
        return $query->whereHas('invoice.receipt', fn ($q) => $q->whereNotNull('proof_photo_path'));
    }

    public function scopeUnbilled($query)
    {
        return $query->whereDoesntHave('invoice.receipt', fn ($q) => $q->whereNotNull('proof_photo_path'));
    }
}
