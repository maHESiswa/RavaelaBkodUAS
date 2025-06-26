<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Periksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Real-time statistics
        $stats = [
            'total_pasien_hari_ini' => DaftarPoli::whereDate('created_at', today())->count(),
            'total_pemeriksaan_hari_ini' => Periksa::whereDate('tgl_periksa', today())->count(),
            'pendapatan_hari_ini' => Periksa::whereDate('tgl_periksa', today())->sum('biaya_periksa'),
            'total_pasien' => Pasien::count(),
            'total_dokter' => Dokter::count(),
            'total_poli' => Poli::count(),
            'total_obat' => Obat::count(),
        ];

        // Antrian per poli hari ini
        $antrianPerPoli = DB::table('daftar_poli')
            ->join('jadwal_periksa', 'daftar_poli.id_jadwal', '=', 'jadwal_periksa.id')
            ->join('dokter', 'jadwal_periksa.id_dokter', '=', 'dokter.id')
            ->join('poli', 'dokter.id_poli', '=', 'poli.id')
            ->whereDate('daftar_poli.created_at', today())
            ->select('poli.nama_poli', DB::raw('COUNT(*) as jumlah_antrian'))
            ->groupBy('poli.id', 'poli.nama_poli')
            ->get();

        // Chart data - kunjungan mingguan
        $kunjunganMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $kunjunganMingguan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'hari' => $date->format('D'),
                'jumlah' => DaftarPoli::whereDate('created_at', $date)->count()
            ];
        }

        // Distribusi poli (pie chart data)
        $distribusiPoli = DB::table('daftar_poli')
            ->join('jadwal_periksa', 'daftar_poli.id_jadwal', '=', 'jadwal_periksa.id')
            ->join('dokter', 'jadwal_periksa.id_dokter', '=', 'dokter.id')
            ->join('poli', 'dokter.id_poli', '=', 'poli.id')
            ->whereMonth('daftar_poli.created_at', now()->month)
            ->select('poli.nama_poli', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('poli.id', 'poli.nama_poli')
            ->get();

        // Recent activities
        $recentActivities = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'antrianPerPoli',
            'kunjunganMingguan',
            'distribusiPoli',
            'recentActivities'
        ));
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'weekly');
        
        if ($type === 'weekly') {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = today()->subDays($i);
                $data[] = [
                    'label' => $date->format('M d'),
                    'value' => DaftarPoli::whereDate('created_at', $date)->count()
                ];
            }
        } else if ($type === 'monthly') {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = today()->subMonths($i);
                $data[] = [
                    'label' => $date->format('M Y'),
                    'value' => DaftarPoli::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count()
                ];
            }
        }

        return response()->json($data);
    }

    public function getStats()
    {
        $stats = [
            'total_pasien_hari_ini' => DaftarPoli::whereDate('created_at', today())->count(),
            'total_pemeriksaan_hari_ini' => Periksa::whereDate('tgl_periksa', today())->count(),
            'pendapatan_hari_ini' => Periksa::whereDate('tgl_periksa', today())->sum('biaya_periksa'),
            'antrian_aktif' => DaftarPoli::whereDate('created_at', today())
                ->whereDoesntHave('periksa')
                ->count(),
        ];

        return response()->json($stats);
    }
}
