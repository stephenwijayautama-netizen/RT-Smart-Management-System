<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    // GET /api/houses
    public function index()
    {
        return response()->json(House::all(), 200);
    }

    // POST /api/houses
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_id' => 'required|unique:houses',
            'nomor_rumah' => 'required|unique:houses',
            'status_rumah' => 'required|in:DIHUNI,TIDAK_DIHUNI',
            'keterangan' => 'nullable|string',
        ]);

        $house = House::create($validated);

        return response()->json([
            'message' => 'House created successfully',
            'data' => $house
        ], 201);
    }

    // GET /api/houses/{id}
    public function show($id)
    {
        $house = House::find($id);

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        return response()->json($house, 200);
    }

    // PUT /api/houses/{id}
    public function update(Request $request, $id)
    {
        $house = House::find($id);

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        $validated = $request->validate([
            'house_id' => 'sometimes|unique:houses,house_id,' . $id,
            'nomor_rumah' => 'sometimes|unique:houses,nomor_rumah,' . $id,
            'status_rumah' => 'sometimes|in:DIHUNI,TIDAK_DIHUNI',
            'keterangan' => 'nullable|string',
        ]);

        $house->update($validated);

        return response()->json([
            'message' => 'House updated successfully',
            'data' => $house
        ], 200);
    }

    // DELETE /api/houses/{id}
    public function destroy($id)
    {
        $house = House::find($id);

        if (!$house) {
            return response()->json(['message' => 'House not found'], 404);
        }

        $house->delete();

        return response()->json([
            'message' => 'House deleted successfully'
        ], 200);
    }
}