<?php

namespace App\Http\Controllers;

use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\User;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PeriksaController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:dokter')->except(['index', 'show']);
    }

    public function index()
    {
        $user = Auth::user();
        $periksas = [];
        
        if ($user->role === 'dokter') {
            $periksas = Periksa::with(['pasien', 'detailPeriksas.obat'])
                            ->where('id_dokter', $user->id)
                            ->latest()
                            ->get();
        } else {
            $periksas = Periksa::with(['dokter', 'detailPeriksas.obat'])
                            ->where('id_pasien', $user->id)
                            ->latest()
                            ->get();
        }

        return view('periksa.index', compact('periksas'));
    }

    public function create()
    {
        $pasiens = User::where('role', 'pasien')->orderBy('nama')->get();
        $obats = Obat::orderBy('nama_obat')->get();
        return view('periksa.create', compact('pasiens', 'obats'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $messages = [
                'id_pasien.required' => 'Pasien harus dipilih',
                'id_pasien.exists' => 'Pasien tidak ditemukan',
                'tgl_periksa.required' => 'Tanggal periksa harus diisi',
                'tgl_periksa.date' => 'Format tanggal periksa tidak valid',
                'tgl_periksa.before_or_equal' => 'Tanggal periksa tidak boleh lebih dari hari ini',
                'catatan.required' => 'Catatan harus diisi',
                'catatan.min' => 'Catatan minimal 10 karakter',
                'biaya_periksa.required' => 'Biaya periksa harus diisi',
                'biaya_periksa.integer' => 'Biaya periksa harus berupa angka',
                'biaya_periksa.min' => 'Biaya periksa minimal Rp 50.000',
                'obat_id.required' => 'Minimal satu obat harus dipilih',
                'obat_id.array' => 'Format obat tidak valid',
                'obat_id.*.exists' => 'Obat yang dipilih tidak valid'
            ];

            $validated = $request->validate([
                'id_pasien' => 'required|exists:users,id,role,pasien',
                'tgl_periksa' => 'required|date|before_or_equal:now',
                'catatan' => 'required|string|min:10',
                'biaya_periksa' => 'required|integer|min:50000',
                'obat_id' => 'required|array',
                'obat_id.*' => 'exists:obats,id'
            ], $messages);

            // Verify if patient already has examination on the same day
            $existingPeriksa = Periksa::where('id_pasien', $request->id_pasien)
                ->whereDate('tgl_periksa', date('Y-m-d', strtotime($request->tgl_periksa)))
                ->exists();

            if ($existingPeriksa) {
                return back()
                    ->withInput()
                    ->withErrors(['id_pasien' => 'Pasien sudah memiliki pemeriksaan di tanggal yang sama']);
            }

            // Create periksa record
            $periksa = Periksa::create([
                'id_pasien' => $validated['id_pasien'],
                'id_dokter' => Auth::id(),
                'tgl_periksa' => $validated['tgl_periksa'],
                'catatan' => $validated['catatan'],
                'biaya_periksa' => $validated['biaya_periksa']
            ]);

            // Create detail periksa records
            foreach($validated['obat_id'] as $obat_id) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat_id
                ]);
            }

            DB::commit();
            toastr()->success('Data pemeriksaan berhasil disimpan');
            return redirect()->route('periksa.index');

        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Terjadi kesalahan. Data pemeriksaan gagal disimpan.');
            return back()->withInput();
        }
    }

    public function show(Periksa $periksa)
    {
        $user = Auth::user();

        if ($user->role === 'pasien' && $periksa->id_pasien !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'dokter' && $periksa->id_dokter !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $periksa->load(['pasien', 'dokter', 'detailPeriksas.obat']);
        return view('periksa.show', compact('periksa'));
    }

    public function edit(Periksa $periksa)
    {
        if ($periksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pasiens = User::where('role', 'pasien')->orderBy('nama')->get();
        $obats = Obat::orderBy('nama_obat')->get();
        $selected_obats = $periksa->detailPeriksas->pluck('id_obat')->toArray();
        
        return view('periksa.edit', compact('periksa', 'pasiens', 'obats', 'selected_obats'));
    }

    public function update(Request $request, Periksa $periksa)
    {
        try {
            if ($periksa->id_dokter !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            DB::beginTransaction();

            $messages = [
                'id_pasien.required' => 'Pasien harus dipilih',
                'id_pasien.exists' => 'Pasien tidak ditemukan',
                'tgl_periksa.required' => 'Tanggal periksa harus diisi',
                'tgl_periksa.date' => 'Format tanggal periksa tidak valid',
                'tgl_periksa.before_or_equal' => 'Tanggal periksa tidak boleh lebih dari hari ini',
                'catatan.required' => 'Catatan harus diisi',
                'catatan.min' => 'Catatan minimal 10 karakter',
                'biaya_periksa.required' => 'Biaya periksa harus diisi',
                'biaya_periksa.integer' => 'Biaya periksa harus berupa angka',
                'biaya_periksa.min' => 'Biaya periksa minimal Rp 50.000',
                'obat_id.required' => 'Minimal satu obat harus dipilih',
                'obat_id.array' => 'Format obat tidak valid',
                'obat_id.*.exists' => 'Obat yang dipilih tidak valid'
            ];

            $validated = $request->validate([
                'id_pasien' => 'required|exists:users,id,role,pasien',
                'tgl_periksa' => 'required|date|before_or_equal:now',
                'catatan' => 'required|string|min:10',
                'biaya_periksa' => 'required|integer|min:50000',
                'obat_id' => 'required|array',
                'obat_id.*' => 'exists:obats,id'
            ], $messages);

            // Verify if another patient has examination on the same day (exclude current record)
            $existingPeriksa = Periksa::where('id_pasien', $request->id_pasien)
                ->where('id', '!=', $periksa->id)
                ->whereDate('tgl_periksa', date('Y-m-d', strtotime($request->tgl_periksa)))
                ->exists();

            if ($existingPeriksa) {
                return back()
                    ->withInput()
                    ->withErrors(['id_pasien' => 'Pasien sudah memiliki pemeriksaan di tanggal yang sama']);
            }

            // Update periksa record
            $periksa->update([
                'id_pasien' => $validated['id_pasien'],
                'tgl_periksa' => $validated['tgl_periksa'],
                'catatan' => $validated['catatan'],
                'biaya_periksa' => $validated['biaya_periksa']
            ]);

            // Delete existing detail periksa
            $periksa->detailPeriksas()->delete();

            // Create new detail periksa records
            foreach($validated['obat_id'] as $obat_id) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat_id
                ]);
            }

            DB::commit();
            toastr()->success('Data pemeriksaan berhasil diupdate');
            return redirect()->route('periksa.index');

        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Terjadi kesalahan. Data pemeriksaan gagal diupdate.');
            return back()->withInput();
        }
    }

    public function destroy(Periksa $periksa)
    {
        try {
            if ($periksa->id_dokter !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            DB::beginTransaction();
            $periksa->detailPeriksas()->delete();
            $periksa->delete();
            DB::commit();

            toastr()->success('Data pemeriksaan berhasil dihapus');
            return redirect()->route('periksa.index');

        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Terjadi kesalahan. Data pemeriksaan gagal dihapus.');
            return back();
        }
    }
}