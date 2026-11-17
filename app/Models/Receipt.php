<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'invoice_id',
        'receipt_date',
        'amount',
        'amount_in_words',
        'payment_purpose',
        'proof_number',
        'proof_date',
        'proof_photo_path',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'proof_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function isEditable(): bool
    {
        return $this->invoice?->order?->isEditable() ?? true;
    }

}