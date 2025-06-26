<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = Pasien::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = Pasien::$rules;
        
        // Add phone number validation with proper regex
        $rules['no_hp'] = [
            'required',
            'string',
            'max:50',
            'regex:/^(\+62|08)[0-9]{8,12}$/'
        ];



        try {
            Pasien::create($request->all());
            toastr()->success('Pasien berhasil ditambahkan');
            return redirect()->route('pasien.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menambahkan pasien: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        return view('admin.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $rules = Pasien::$rules;
        $rules['no_ktp'] = 'required|string|size:16|unique:pasien,no_ktp,' . $pasien->id;
        $rules['no_rm'] = 'required|string|max:25|unique:pasien,no_rm,' . $pasien->id;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toastr()->error('Data tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pasien->update($request->all());
            toastr()->success('Pasien berhasil diperbarui');
            return redirect()->route('pasien.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal memperbarui pasien: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        try {
            // Check if pasien has any daftar_poli records
            if ($pasien->daftarPolis()->count() > 0) {
                toastr()->error('Tidak dapat menghapus pasien yang memiliki riwayat pendaftaran');
                return redirect()->back();
            }

            $pasien->delete();
            toastr()->success('Pasien berhasil dihapus');
            return redirect()->route('pasien.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menghapus pasien: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * API endpoint for getting pasien data
     */
    public function api()
    {
        $pasiens = Pasien::all();
        return response()->json($pasiens);
    }
}
