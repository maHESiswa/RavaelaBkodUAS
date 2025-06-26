@extends('layout.admin')

@section('title', 'Manajemen Dokter')
@section('page-title', 'Manajemen Dokter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Dokter</li>
@endsection

@section('page-actions')
    <a href="{{ route('dokter.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Dokter
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Daftar Dokter</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Poli</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Jadwal</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokters as $index => $dokter)
                    <tr>
                        <td>{{ $dokters->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $dokter->nama }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $dokter->poli->nama_poli ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $dokter->alamat }}</td>
                        <td>{{ $dokter->no_hp }}</td>
                        <td>
                            <span class="badge bg-info">{{ $dokter->jadwalPeriksas->count() ?? 0 }} Jadwal</span>
                        </td>
                        <td>{{ $dokter->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dokter.show', $dokter) }}" class="btn btn-outline-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dokter.edit', $dokter) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dokter.destroy', $dokter) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus dokter ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-user-md fa-3x mb-3"></i>
                            <p>Belum ada data dokter</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dokters->hasPages())
        <div class="card-footer bg-white">
            {{ $dokters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection