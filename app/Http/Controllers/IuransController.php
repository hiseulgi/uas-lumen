<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Warga;
use Carbon\Carbon;

class IuransController extends Controller
{
    public function index()
    {
        $iurans = Iuran::all();
        return response()->json([
            'data' => $iurans
        ], 200);
    }

    public function show($id)
    {
        try {
            $iuran = Iuran::findOrFail($id);
            return response()->json([
                'data' => $iuran
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Iuran not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_warga' => 'required|exists:wargas,id',
            'bulan' => 'required|date_format:Y-m',
            'jumlah_iuran' => 'required|integer',
            'status' => 'required|in:pending,selesai',
        ]);

        $request->merge([
            'bulan' => $request->bulan . '-01',
        ]);

        $iuran = Iuran::create($request->all());
        return response()->json([
            'message' => 'Data iuran berhasil ditambahkan',
            'data' => $iuran
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $iuran = Iuran::findOrFail($id);
            $this->validate($request, [
                'status' => 'required|in:pending,selesai',
            ]);
            $iuran->update($request->only('status'));
            return response()->json([
                'message' => 'Data iuran berhasil diperbarui',
                'data' => $iuran
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Iuran not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $iuran = Iuran::findOrFail($id);
            $iuran->delete();
            return response()->json(['message' => 'Data iuran berhasil dihapus']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Iuran not found'], 404);
        }
    }

    public function getTunggakan($tahun)
    {
        // Validate the year input
        if (!is_numeric($tahun) || strlen($tahun) != 4) {
            return response()->json(['error' => 'Invalid year format'], 400);
        }

        // Fetch warga data with related iuran filtered by year
        $wargaData = Warga::with(['iurans' => function($query) use ($tahun) {
            $query->whereYear('bulan', $tahun)->orderBy('bulan');
        }])->get();

        // Format the response data
        $response = $wargaData->map(function ($warga) {
            // Group iuran by month and sum the jumlah_iuran for the same month
            $groupedIuran = $warga->iurans->groupBy(function ($iuran) {
                return Carbon::parse($iuran->bulan)->format('Y-m');
            })->map(function ($group) {
                return [
                    'bulan' => $group->first()->bulan,
                    'jumlah_iuran' => $group->sum('jumlah_iuran'),
                ];
            })->values()->sortBy('bulan')->values();

            return [
                'id' => $warga->id,
                'nama' => $warga->nama,
                'alamat' => $warga->alamat,
                'total_iuran' => $warga->iurans->sum('jumlah_iuran'),
                'detail_iuran' => $groupedIuran
            ];
        });

        return response()->json(['data' => $response]);
    }
}