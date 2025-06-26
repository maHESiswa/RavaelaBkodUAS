@extends('layout.admin')

@section('title', 'Detail Pasien')
@section('page-title', 'Detail Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Pasien</a></li>
    <li class="breadcrumb-item active">{{ $pasien->nama }}</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Patient Information -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Pasien</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <p class="h5">{{ $pasien->nama }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor Rekam Medis</label>
                            <p class="h5">
                                <span class="badge bg-info fs-6">{{ $pasien->no_rm }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor KTP</label>
                            <p>{{ $pasien->no_ktp }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor HP</label>
                            <p>{{ $pasien->no_hp }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Alamat</label>
                    <p>{{ $pasien->alamat }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Terdaftar Pada</label>
                            <p>{{ $pasien->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Terakhir Diperbarui</label>
                            <p>{{ $pasien->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        @if($pasien->daftarPolis->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Riwayat Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>No. Antrian</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasien->daftarPolis->sortByDesc('created_at') as $daftar)
                            <tr>
                                <td>{{ $daftar->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}</td>
                                <td>{{ $daftar->jadwalPeriksa->dokter->nama ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $daftar->no_antrian }}</span>
                                </td>
                                <td>{{ Str::limit($daftar->keluhan, 50) }}</td>
                                <td>
                                    @php
                                        $status = $daftar->status ?? 'Menunggu';
                                        $badgeClass = match($status) {
                                            'Selesai' => 'bg-success',
                                            'Sedang Diperiksa' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Statistik Pasien</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Kunjungan</span>
                    <span class="badge bg-primary">{{ $pasien->daftarPolis->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Status</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Nomor RM</span>
                    <span class="badge bg-info">{{ $pasien->no_rm }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span>Dapat Dihapus</span>
                    @if($pasien->daftarPolis->count() === 0)
                        <span class="badge bg-success">Ya</span>
                    @else
                        <span class="badge bg-warning">Tidak</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Pasien
                    </a>
                    
                    @if($pasien->daftarPolis->count() === 0)
                    <form action="{{ route('pasien.destroy', $pasien) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus pasien {{ $pasien->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Pasien
                        </button>
                    </form>
                    @else
                    <button class="btn btn-danger w-100" disabled title="Tidak dapat dihapus karena memiliki riwayat pendaftaran">
                        <i class="fas fa-trash me-2"></i>Hapus Pasien
                    </button>
                    @endif
                    
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection