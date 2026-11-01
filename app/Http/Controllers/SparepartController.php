<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;
use Yajra\DataTables\Facades\DataTables;

class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('spareparts.index');
    }

    public function getData(Request $request)
    {
        $spareparts = Sparepart::query();

        return DataTables::of($spareparts)
            ->addIndexColumn()
            ->setRowId('id')
            ->addColumn('formatted_weight', fn($p) => $p->weight . ' kg')
            ->addColumn('fuzzy_badge', function ($p) {
                return match ((int)$p->fuzzy_weight_value) {
                    1 => '<span class="badge bg-success">1 - Ringan</span>',
                    2 => '<span class="badge bg-warning text-dark">2 - Sedang</span>',
                    3 => '<span class="badge bg-danger">3 - Berat</span>',
                    default => '<span class="badge bg-secondary">-</span>',
                };
            })
            ->addColumn('formatted_price', fn($p) => $p->price ? 'Rp ' . number_format($p->price, 0, ',', '.') : '-')
            ->addColumn('action', function ($p) {
                return '
                    <button class="btn btn-sm btn-warning edit-btn" data-id="'.$p->id.'"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$p->id.'"><i class="bi bi-trash"></i></button>
                ';
            })
            ->rawColumns(['fuzzy_badge', 'action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:30',
            'type' => 'required|in:tubetype,tubeless',
            'weight' => 'required|numeric|min:0',
            'fuzzy_weight_value' => 'required|in:1,2,3',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $sparepart = Sparepart::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sparepart berhasil ditambahkan!',
            'data' => $sparepart
        ]);
    }

    public function show($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        return response()->json($sparepart);
    }

    public function update(Request $request, $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'brand' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:30',
            'type' => 'required|in:tubetype,tubeless',
            'weight' => 'required|numeric|min:0',
            'fuzzy_weight_value' => 'required|in:1,2,3',
            'stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
        ]);

        $sparepart->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sparepart berhasil diperbarui!',
            'data' => $sparepart
        ]);
    }

    public function destroy($id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sparepart berhasil dihapus!'
        ]);
    }
}
