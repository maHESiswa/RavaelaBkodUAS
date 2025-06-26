@extends('layout.admin')

@section('title', 'Manajemen Pasien')
@section('page-title', 'Manajemen Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pasien</li>
@endsection

@section('page-actions')
    <a href="{{ route('pasien.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pasien
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Daftar Pasien</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>No. KTP</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $index => $pasien)
                    <tr>
                        <td>{{ $pasiens->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-info">{{ $pasien->no_rm }}</span>
                        </td>
                        <td>
                            <strong>{{ $pasien->nama }}</strong>
                        </td>
                        <td>{{ $pasien->no_ktp }}</td>
                        <td>{{ Str::limit($pasien->alamat, 30) }}</td>
                        <td>{{ $pasien->no_hp }}</td>
                        <td>{{ $pasien->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-outline-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pasien.destroy', $pasien) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
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
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Belum ada data pasien</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pasiens->hasPages())
        <div class="card-footer bg-white">
            {{ $pasiens->links() }}
        </div>
        @endif
    </div>
</div>
@endsection