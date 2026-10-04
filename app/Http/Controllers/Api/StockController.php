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

    public function movements(Request $request) {
        $query = StockMovement::with(['item', 'user']);

        if ($request->boolean('mine')) {
            $query->where('user_id', $request->user()->id);
        }

        $movements = $query->latest()->paginate();

        return response()->json($movements);
    }

    public function updateMovement(Request $request, StockMovement $movement)
    {
        if ($movement->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        if ($movement->movement_type !== 'out') {
            return response()->json(['message' => 'Hanya barang keluar yang bisa diedit'], 422);
        }

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        try {
            $updatedMovement = $this->stockService->updateStockOut(
                $movement,
                (float) $validated['quantity'],
                $validated['note'] ?? null
            );

            return response()->json([
                'message' => 'Barang keluar berhasil diperbarui',
                'data' => $updatedMovement,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
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
