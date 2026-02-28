<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        return response()->json(ExpenseCategory::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_iuran'       => 'required|string|max:255',
            'jumlah'            => 'required|numeric|min:0',
            'tanggal_pembayaran'=> 'required|date',
        ]);

        $category = ExpenseCategory::create($validated);

        return response()->json(['message' => 'Kategori berhasil ditambahkan', 'data' => $category], 201);
    }
}
