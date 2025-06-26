@extends('layout.admin')

@section('title', 'Detail Dokter')
@section('page-title', 'Detail Dokter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dokter.index') }}">Dokter</a></li>
    <li class="breadcrumb-item active">{{ $dokter->nama }}</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('dokter.edit', $dokter) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('dokter.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Doctor Information -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Dokter</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Dokter</label>
                            <p class="h5">{{ $dokter->nama }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Poli</label>
                            <p class="h5">
                                <span class="badge bg-primary fs-6">{{ $dokter->poli->nama_poli ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Alamat</label>
                    <p>{{ $dokter->alamat }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor HP</label>
                            <p>{{ $dokter->no_hp }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Jumlah Jadwal</label>
                            <p>
                                <span class="badge bg-info">{{ $dokter->jadwalPeriksas->count() }} Jadwal</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Bergabung Pada</label>
                            <p>{{ $dokter->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Terakhir Diperbarui</label>
                            <p>{{ $dokter->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule List -->
        @if($dokter->jadwalPeriksas->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Jadwal Praktek</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dokter->jadwalPeriksas as $jadwal)
                            <tr>
                                <td><strong>{{ $jadwal->hari }}</strong></td>
                                <td>{{ $jadwal->jam_mulai }}</td>
                                <td>{{ $jadwal->jam_selesai }}</td>
                                <td>
                                    @php
                                        $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                        $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                        $duration = $start->diffInHours($end);
                                    @endphp
                                    {{ $duration }} jam
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
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
                <h5 class="card-title mb-0">Statistik Dokter</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Poli</span>
                    <span class="badge bg-primary">{{ $dokter->poli->nama_poli ?? 'N/A' }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Jadwal Praktek</span>
                    <span class="badge bg-info">{{ $dokter->jadwalPeriksas->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Status</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span>Dapat Dihapus</span>
                    @if($dokter->canBeDeleted())
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
                    <a href="{{ route('dokter.edit', $dokter) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Dokter
                    </a>
                    
                    @if($dokter->canBeDeleted())
                    <form action="{{ route('dokter.destroy', $dokter) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus dokter {{ $dokter->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Dokter
                        </button>
                    </form>
                    @else
                    <button class="btn btn-danger w-100" disabled title="Tidak dapat dihapus karena memiliki jadwal aktif">
                        <i class="fas fa-trash me-2"></i>Hapus Dokter
                    </button>
                    @endif
                    
                    <a href="{{ route('dokter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection