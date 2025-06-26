<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DaftarPoliController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalPeriksaController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PeriksaController;
use App\Http\Controllers\PoliController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page');
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'Server is working!', 'timestamp' => now()]);
});

// Route Auth
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Master Data Management
    Route::resource('poli', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    
    // API endpoints for admin
    Route::get('/api/stats', [DashboardController::class, 'getStats']);
    Route::get('/api/chart-data', [DashboardController::class, 'getChartData']);
    Route::get('/api/pasien', [PasienController::class, 'api']);
});

// Route Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.index');
    })->name('dokter.dashboard');

    Route::get('/obat', [ObatController::class, 'index']);
    Route::get('/obat/create', [ObatController::class, 'create']);
    Route::post('/obat', [ObatController::class, 'store']);
    Route::get('/obat/{id}/edit', [ObatController::class, 'edit']);
    Route::put('/obat/{id}', [ObatController::class, 'update']);
    Route::delete('/obat/{id}', [ObatController::class, 'destroy']);
    
    // Jadwal Periksa untuk dokter
    Route::get('/jadwal', [JadwalPeriksaController::class, 'index'])->name('dokter.jadwal.index');
    Route::get('/jadwal/create', [JadwalPeriksaController::class, 'create'])->name('dokter.jadwal.create');
    Route::post('/jadwal', [JadwalPeriksaController::class, 'store'])->name('dokter.jadwal.store');
    Route::get('/jadwal/{jadwal}/edit', [JadwalPeriksaController::class, 'edit'])->name('dokter.jadwal.edit');
    Route::put('/jadwal/{jadwal}', [JadwalPeriksaController::class, 'update'])->name('dokter.jadwal.update');
    Route::delete('/jadwal/{jadwal}', [JadwalPeriksaController::class, 'destroy'])->name('dokter.jadwal.destroy');
    
    // Pemeriksaan
    Route::get('/periksa', [PeriksaController::class, 'index'])->name('dokter.periksa.index');
    Route::get('/periksa/create', [PeriksaController::class, 'create'])->name('dokter.periksa.create');
    Route::post('/periksa', [PeriksaController::class, 'store'])->name('dokter.periksa.store');
    Route::get('/periksa/{periksa}/edit', [PeriksaController::class, 'edit'])->name('dokter.periksa.edit');
    Route::put('/periksa/{periksa}', [PeriksaController::class, 'update'])->name('dokter.periksa.update');
    Route::get('/periksa/{periksa}', [PeriksaController::class, 'show'])->name('dokter.periksa.show');
});

// Route Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.index');
    })->name('pasien.dashboard');
    
    // Pendaftaran Poli
    Route::get('/daftar-poli', [DaftarPoliController::class, 'index'])->name('pasien.daftar-poli.index');
    Route::get('/daftar-poli/create', [DaftarPoliController::class, 'create'])->name('pasien.daftar-poli.create');
    Route::post('/daftar-poli', [DaftarPoliController::class, 'store'])->name('pasien.daftar-poli.store');
    Route::get('/daftar-poli/{daftarPoli}', [DaftarPoliController::class, 'show'])->name('pasien.daftar-poli.show');
    
    // Riwayat Pemeriksaan
    Route::get('/riwayat', [PeriksaController::class, 'riwayat'])->name('pasien.riwayat');
});

// Public API Routes
Route::prefix('api')->group(function () {
    Route::get('/poli', [PoliController::class, 'api']);
    Route::get('/dokter', [DokterController::class, 'api']);
    Route::get('/jadwal/{poli_id}', [JadwalPeriksaController::class, 'getByPoli']);
});

// Legacy routes for backward compatibility
Route::middleware(['auth'])->group(function () {
    Route::get('/periksa', [PeriksaController::class, 'index'])->name('periksa.index');
    Route::get('/periksa/create', [PeriksaController::class, 'create'])->name('periksa.create');
    Route::post('/periksa', [PeriksaController::class, 'store'])->name('periksa.store');
    Route::get('/periksa/{periksa}', [PeriksaController::class, 'show'])->name('periksa.show');
    Route::get('/periksa/{periksa}/edit', [PeriksaController::class, 'edit'])->name('periksa.edit');
    Route::put('/periksa/{periksa}', [PeriksaController::class, 'update'])->name('periksa.update');
    Route::delete('/periksa/{periksa}', [PeriksaController::class, 'destroy'])->name('periksa.destroy');
});
