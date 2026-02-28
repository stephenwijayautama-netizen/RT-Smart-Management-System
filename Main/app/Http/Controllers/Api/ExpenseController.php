<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // ===============================
    // GET ALL EXPENSE
    // ===============================
    public function index()
    {
        $expenses = Expense::with(['house', 'occupant', 'category'])
            ->latest()
            ->get();

        return response()->json($expenses, 200);
    }

    // ===============================
    // GET DETAIL EXPENSE
    // ===============================
    public function show($id)
    {
        $expense = Expense::with(['house', 'occupant', 'category'])
            ->find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($expense, 200);
    }

    // ===============================
    // STORE EXPENSE
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_id' => 'required|exists:houses,id',
            'occupant_id' => 'required|exists:occupants,id',
            'category_id' => 'required|exists:expense_categories,id',
            'durasi' => 'nullable|integer|min:1',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:BELUM_BAYAR,SUDAH_BAYAR',
            'tanggal_pembayaran' => 'nullable|date',
        ]);

        $expense = Expense::create($validated);

        // --- INTEGRASI SIMULASI DOKU ---
        $paymentId = 'INV-' . time() . '-' . $expense->id;
        $paymentUrl = url("/doku-simulation?id={$expense->id}&amount={$expense->jumlah}&inv={$paymentId}");
        
        $expense->update([
            'metode_pembayaran' => 'DOKU',
            'payment_id' => $paymentId,
            'payment_url' => $paymentUrl,
            'payment_status' => 'PENDING'
        ]);

        return response()->json([
            'message' => 'Tagihan berhasil dibuat, silakan lanjut ke pembayaran',
            'data' => $expense,
            'payment_url' => $paymentUrl
        ], 201);
    }

    // ===============================
    // UPDATE EXPENSE
    // ===============================
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'house_id' => 'sometimes|exists:houses,id',
            'occupant_id' => 'sometimes|exists:occupants,id',
            'category_id' => 'sometimes|exists:expense_categories,id',
            'durasi' => 'nullable|integer|min:1',
            'jumlah' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:BELUM_BAYAR,SUDAH_BAYAR',
            'tanggal_pembayaran' => 'nullable|date',
        ]);

        $expense->update($validated);

        return response()->json([
            'message' => 'Expense berhasil diperbarui',
            'data' => $expense
        ], 200);
    }

    // ===============================
    // DELETE EXPENSE
    // ===============================
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense berhasil dihapus'
        ], 200);
    }
}