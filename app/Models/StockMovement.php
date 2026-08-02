<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

#[Fillable(['branch_id','user_id', 'item_id', 'movement_type', 'quantity', 'note'])]
class StockMovement extends Model
{
    protected function casts():array {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    protected static function booted(): void {
        static::addGlobalScope(new BranchScope);
    }

    public function branch(): BelongsTo {
        return $this->belongsTo(Branch::class);
    }

    public function item(): BelongsTo {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}