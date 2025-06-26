<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polis = Poli::withCount('dokters')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.poli.index', compact('polis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.poli.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Poli::$rules);

        if ($validator->fails()) {
            toastr()->error('Data tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Poli::create($request->all());
            toastr()->success('Poli berhasil ditambahkan');
            return redirect()->route('poli.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menambahkan poli: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Poli $poli)
    {
        $poli->load('dokters');
        return view('admin.poli.show', compact('poli'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poli $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poli $poli)
    {
        $rules = Poli::$rules;
        $rules['nama_poli'] = 'required|string|max:25|unique:poli,nama_poli,' . $poli->id;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            toastr()->error('Data tidak valid');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $poli->update($request->all());
            toastr()->success('Poli berhasil diperbarui');
            return redirect()->route('poli.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal memperbarui poli: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poli $poli)
    {
        try {
            if (!$poli->canBeDeleted()) {
                toastr()->error('Tidak dapat menghapus poli yang memiliki dokter');
                return redirect()->back();
            }

            $poli->delete();
            toastr()->success('Poli berhasil dihapus');
            return redirect()->route('poli.index');
        } catch (\Exception $e) {
            toastr()->error('Gagal menghapus poli: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * API endpoint for getting poli data
     */
    public function api()
    {
        $polis = Poli::all();
        return response()->json($polis);
    }
}
