<?php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['branch_id', 'item_id', 'quantity'])]
class BranchStock extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    protected static function booted(): void {
        static::addGlobalScope(new BranchScope);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}