<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'unit', 'is_active', 'category_id'])]
class Item extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function branchStocks() {
        return $this->hasMany(BranchStock::class);
    }

    public function stockMovements() {
        return $this->hasMany(StockMovement::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}