@extends('layout.app')

@section('title', 'Data Pemeriksaan')

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
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pemeriksaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ auth()->user()->role === 'dokter' ? route('dokter.dashboard') : route('pasien.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pemeriksaan</li>
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
                        @if(auth()->user()->role === 'dokter')
                        <a href="{{ route('periksa.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Tambah Pemeriksaan
                        </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="tabelPemeriksaan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if(auth()->user()->role === 'dokter')
                                    <th>Pasien</th>
                                    @else
                                    <th>Dokter</th>
                                    @endif
                                    <th>Tanggal Periksa</th>
                                    <th>Catatan</th>
                                    <th>Biaya</th>
                                    <th>Obat</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periksas as $periksa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if(auth()->user()->role === 'dokter')
                                    <td>{{ $periksa->pasien->nama }}</td>
                                    @else
                                    <td>{{ $periksa->dokter->nama }}</td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</td>
                                    <td>{{ \Str::limit($periksa->catatan, 50) }}</td>
                                    <td>Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</td>
                                    <td>
                                        @foreach($periksa->detailPeriksas as $detail)
                                        - {{ $detail->obat->nama_obat }}<br>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('periksa.show', $periksa->id) }}" class="btn btn-info btn-sm" 
                                            data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'dokter')
                                        <a href="{{ route('periksa.edit', $periksa->id) }}" class="btn btn-warning btn-sm"
                                            data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                            data-id="{{ $periksa->id }}"
                                            data-pasien="{{ $periksa->pasien->nama }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}"
                                            data-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="form-hapus-{{ $periksa->id }}" 
                                            action="{{ route('periksa.destroy', $periksa->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function () {
            // Initialize DataTable
            $("#tabelPemeriksaan").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                }
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

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