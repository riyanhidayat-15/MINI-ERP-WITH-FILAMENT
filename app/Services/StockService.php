<?php

namespace App\Services;

use App\Models\BranchStock;
use App\Models\MovementStock;
use App\Models\StockMovement;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class StockService {
    public function stockIn(User $user, int $itemId, float $qty, ?string $note = null): StockMovement {
        return DB::transaction(function () use ($user, $itemId, $qty, $note) {
            $branchId = $user->branch_id;

            $stock = BranchStock::withoutGlobalScopes()
                ->firstOrCreate(
                    ['branch_id' => $branchId, 'item_id' => $itemId],
                    ['quantity' => 0]
                );

            $stock->increment('quantity', $qty);

            return StockMovement::create([
                'branch_id' => $branchId,
                'user_id' => $user->id,
                'item_id' => $itemId,
                'movement_type' => 'in',
                'quantity' => $qty,
                'note' => $note,
            ]);
        });
    }

    public function stockOut(User $user, int $itemId, float $qty, ?string $note = null): StockMovement {
        return DB::transaction(function () use ($user, $itemId, $qty, $note) {
            $branchId = $user->branch_id;

            $stock = BranchStock::withoutGlobalScopes()
                ->where('branch_id', $branchId)
                ->where('item_id', $itemId)
                ->lockForUpdate()
                ->first();

            if (! $stock || $stock->quantity < $qty) {
                throw new Exception('Stok tidak cukup');
            }

            $stock->decrement('quantity', $qty);

            return StockMovement::create([
                'branch_id' => $branchId,
                'user_id' => $user->id,
                'item_id' => $itemId,
                'movement_type' => 'out',
                'quantity' => $qty,
                'note' => $note,
            ]);
        });
    }
}