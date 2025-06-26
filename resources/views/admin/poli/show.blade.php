@extends('layout.admin')

@section('title', 'Detail Poli')
@section('page-title', 'Detail Poli')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('poli.index') }}">Poli</a></li>
    <li class="breadcrumb-item active">{{ $poli->nama_poli }}</li>
@endsection

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('poli.edit', $poli) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('poli.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Poli Information -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Informasi Poli</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Poli</label>
                            <p class="h5">{{ $poli->nama_poli }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Jumlah Dokter</label>
                            <p class="h5">
                                <span class="badge bg-info fs-6">{{ $poli->dokters->count() }} Dokter</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Keterangan</label>
                    <p>{{ $poli->keterangan ?? 'Tidak ada keterangan' }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Dibuat Pada</label>
                            <p>{{ $poli->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Terakhir Diperbarui</label>
                            <p>{{ $poli->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctors List -->
        @if($poli->dokters->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Daftar Dokter di Poli {{ $poli->nama_poli }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dokter</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($poli->dokters as $index => $dokter)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $dokter->nama }}</strong>
                                </td>
                                <td>{{ $dokter->alamat }}</td>
                                <td>{{ $dokter->no_hp }}</td>
                                <td>{{ $dokter->created_at->format('d/m/Y') }}</td>
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
                <h5 class="card-title mb-0">Statistik Poli</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Dokter</span>
                    <span class="badge bg-primary">{{ $poli->dokters->count() }}</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Status</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span>Dapat Dihapus</span>
                    @if($poli->canBeDeleted())
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
                    <a href="{{ route('poli.edit', $poli) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Poli
                    </a>
                    
                    @if($poli->canBeDeleted())
                    <form action="{{ route('poli.destroy', $poli) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus poli {{ $poli->nama_poli }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Poli
                        </button>
                    </form>
                    @else
                    <button class="btn btn-danger w-100" disabled title="Tidak dapat dihapus karena masih memiliki dokter">
                        <i class="fas fa-trash me-2"></i>Hapus Poli
                    </button>
                    @endif
                    
                    <a href="{{ route('poli.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection