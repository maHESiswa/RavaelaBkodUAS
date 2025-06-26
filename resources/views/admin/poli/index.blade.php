@extends('layout.admin')

@section('title', 'Manajemen Poli')
@section('page-title', 'Manajemen Poli')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Poli</li>
@endsection

@section('page-actions')
    <a href="{{ route('poli.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Poli
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Daftar Poli</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Poli</th>
                        <th>Keterangan</th>
                        <th>Jumlah Dokter</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polis as $index => $poli)
                    <tr>
                        <td>{{ $polis->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $poli->nama_poli }}</strong>
                        </td>
                        <td>{{ $poli->keterangan ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $poli->dokters_count }} Dokter</span>
                        </td>
                        <td>{{ $poli->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('poli.show', $poli) }}" class="btn btn-outline-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('poli.edit', $poli) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('poli.destroy', $poli) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus poli ini?')">
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
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-hospital fa-3x mb-3"></i>
                            <p>Belum ada data poli</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($polis->hasPages())
        <div class="card-footer bg-white">
            {{ $polis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection