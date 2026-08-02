<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'is_active',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(BranchStock::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
