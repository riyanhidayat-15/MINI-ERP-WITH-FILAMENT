<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchStock;
use App\Models\MovementStock;
use App\Models\StockMovement;
use App\Services\StockService;
use Exception;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(protected StockService $stockService) {}
    
    public function stocks() {
        $stoks = BranchStock::with('item')->get();
        return response()->json($stoks);
    }

    public function movements() {
        $movements = StockMovement::with(['item', 'user'])
            ->latest()
            ->paginate();

        return response()->json($movements);
    }

    public function stockIn(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        $movement = $this->stockService->stockIn(
            $request->user(),
            $validated['item_id'],
            $validated['quantity'],
            $validated['note'] ?? null
        );

        return response()->json([
            'message' => 'Barang masuk berhasil dicatat',
            'data' => $movement,
        ], 201);
    }

    public function stockOut(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        try {
            $movement = $this->stockService->stockOut(
                $request->user(),
                $validated['item_id'],
                $validated['quantity'],
                $validated['note'] ?? null
            );

            return response()->json([
                'message' => 'Barang keluar berhasil dicatat',
                'data' => $movement,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
