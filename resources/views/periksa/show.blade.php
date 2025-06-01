@extends('layout.app')

@section('title', 'Detail Pemeriksaan')

@section('nav-item')
    <li class="nav-item">
        <a href="{{ route('dokter.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item menu-open">
        <a href="{{ route('periksa.index') }}" class="nav-link active">
            <i class="nav-icon fas fa-stethoscope"></i>
            <p>
                Pemeriksaan
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('periksa.index') }}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Pemeriksaan</p>
                </a>
            </li>
            @if(auth()->user()->role === 'dokter')
            <li class="nav-item">
                <a href="{{ route('periksa.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tambah Pemeriksaan</p>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @if(auth()->user()->role === 'dokter')
    <li class="nav-item">
        <a href="/dokter/obat" class="nav-link">
            <i class="nav-icon fas fa-pills"></i>
            <p>Obat</p>
        </a>
    </li>
    @endif
@endsection

@push('styles')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pemeriksaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ auth()->user()->role === 'dokter' ? route('dokter.dashboard') : route('pasien.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('periksa.index') }}">Pemeriksaan</a></li>
                    <li class="breadcrumb-item active">Detail Pemeriksaan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="{{ route('periksa.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            @if(auth()->user()->role === 'dokter' && $periksa->id_dokter === auth()->id())
                            <a href="{{ route('periksa.edit', $periksa->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $periksa->id }}"
                                data-pasien="{{ $periksa->pasien->nama }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                            <form id="form-hapus-{{ $periksa->id }}" 
                                action="{{ route('periksa.destroy', $periksa->id) }}" 
                                method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="bg-light" width="200">Pasien</th>
                                            <td>{{ $periksa->pasien->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Dokter</th>
                                            <td>{{ $periksa->dokter->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Tanggal Periksa</th>
                                            <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Catatan</th>
                                            <td>{{ $periksa->catatan }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Biaya Periksa</th>
                                            <td>Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Obat yang Diberikan</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Obat</th>
                                                        <th>Kemasan</th>
                                                        <th class="text-right">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($periksa->detailPeriksas as $detail)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $detail->obat->nama_obat }}</td>
                                                        <td>{{ $detail->obat->kemasan }}</td>
                                                        <td class="text-right">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr class="bg-light">
                                                        <th colspan="3" class="text-right">Total Biaya Obat:</th>
                                                        <th class="text-right">Rp {{ number_format($periksa->detailPeriksas->sum('obat.harga'), 0, ',', '.') }}</th>
                                                    </tr>
                                                    <tr class="bg-light font-weight-bold">
                                                        <th colspan="3" class="text-right">Total Biaya Keseluruhan:</th>
                                                        <th class="text-right">Rp {{ number_format($periksa->biaya_periksa + $periksa->detailPeriksas->sum('obat.harga'), 0, ',', '.') }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {
            // Handle delete confirmation
            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var pasien = $(this).data('pasien');
                var tanggal = $(this).data('tanggal');
                
                Swal.fire({
                    title: 'Hapus Data Pemeriksaan?',
                    html: `Anda akan menghapus data pemeriksaan:<br>
                        <strong>Pasien:</strong> ${pasien}<br>
                        <strong>Tanggal:</strong> ${tanggal}<br><br>
                        Data yang sudah dihapus tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(`#form-hapus-${id}`).submit();
                    }
                });
            });
        });
    </script>
@endpush