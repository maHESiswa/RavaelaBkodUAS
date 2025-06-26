<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = Dokter::with('poli')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polis = Poli::orderBy('nama_poli')->get();
        return view('admin.dokter.create', compact('polis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = Dokter::$rules;
        
        // Add phone number validation with proper regex
        $rules['no_hp'] = [
            'required',
            'string',
            'max:50',
            'regex:/^(\+62|08)[0-9]{8,12}$/'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toastr()->error('Data tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Dokter::create($request->all());
            toastr()->success('Dokter berhasil ditambahkan');
            return redirect()->route('dokter.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menambahkan dokter: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokter $dokter)
    {
        $dokter->load(['poli', 'jadwalPeriksas']);
        return view('admin.dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokter $dokter)
    {
        $polis = Poli::orderBy('nama_poli')->get();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokter $dokter)
    {
        $rules = Dokter::$rules;
        
        // Add phone number validation with proper regex
        $rules['no_hp'] = [
            'required',
            'string',
            'max:50',
            'regex:/^(\+62|08)[0-9]{8,12}$/'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toastr()->error('Data tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $dokter->update($request->all());
            toastr()->success('Dokter berhasil diperbarui');
            return redirect()->route('dokter.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal memperbarui dokter: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokter $dokter)
    {
        try {
            if (!$dokter->canBeDeleted()) {
                toastr()->error('Tidak dapat menghapus dokter yang memiliki jadwal aktif');
                return redirect()->back();
            }

            $dokter->delete();
            toastr()->success('Dokter berhasil dihapus');
            return redirect()->route('dokter.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menghapus dokter: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * API endpoint for getting dokter data
     */
    public function api()
    {
        $dokters = Dokter::with('poli')->get();
        return response()->json($dokters);
    }
}
