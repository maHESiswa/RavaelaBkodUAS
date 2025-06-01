@extends('layout.app')

@section('title', 'Tambah Pemeriksaan')

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
                <a href="{{ route('periksa.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Pemeriksaan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('periksa.create') }}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tambah Pemeriksaan</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="/dokter/obat" class="nav-link">
            <i class="nav-icon fas fa-pills"></i>
            <p>Obat</p>
        </a>
    </li>
@endsection

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Pemeriksaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('periksa.index') }}">Pemeriksaan</a></li>
                    <li class="breadcrumb-item active">Tambah Pemeriksaan</li>
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
                    <div class="card-body">
                        <form action="{{ route('periksa.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="id_pasien">Pasien <span class="text-danger">*</span></label>
                                <select name="id_pasien" id="id_pasien" class="form-control select2 @error('id_pasien') is-invalid @enderror" required>
                                    <option value="">Pilih Pasien</option>
                                    @foreach($pasiens as $pasien)
                                    <option value="{{ $pasien->id }}" {{ old('id_pasien') == $pasien->id ? 'selected' : '' }}>
                                        {{ $pasien->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Pilih pasien yang akan diperiksa</small>
                                @error('id_pasien')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tgl_periksa">Tanggal Periksa <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('tgl_periksa') is-invalid @enderror" 
                                    id="tgl_periksa" name="tgl_periksa" value="{{ old('tgl_periksa') }}" required>
                                <small class="form-text text-muted">Format: YYYY-MM-DD HH:mm</small>
                                @error('tgl_periksa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="catatan">Catatan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                    id="catatan" name="catatan" rows="3" required 
                                    placeholder="Masukkan catatan hasil pemeriksaan">{{ old('catatan') }}</textarea>
                                <small class="form-text text-muted">Tuliskan hasil pemeriksaan, diagnosis, dan rekomendasi</small>
                                @error('catatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="biaya_periksa">Biaya Periksa <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" class="form-control @error('biaya_periksa') is-invalid @enderror" 
                                        id="biaya_periksa" name="biaya_periksa" value="{{ old('biaya_periksa', 150000) }}" required>
                                </div>
                                <small class="form-text text-muted">Biaya konsultasi dokter (tidak termasuk obat)</small>
                                @error('biaya_periksa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <small class="text-muted d-block">Total Biaya: <span id="totalBiaya">Rp {{ number_format(old('biaya_periksa', 150000), 0, ',', '.') }}</span></small>
                            </div>

                            <div class="form-group">
                                <label for="obat_id">Obat <span class="text-danger">*</span></label>
                                <select name="obat_id[]" id="obat_id" class="form-control select2 @error('obat_id') is-invalid @enderror" 
                                    multiple required data-prices='@json($obats->pluck("harga", "id"))'>
                                    @foreach($obats as $obat)
                                    <option value="{{ $obat->id }}" {{ (old('obat_id') && in_array($obat->id, old('obat_id'))) ? 'selected' : '' }}>
                                        {{ $obat->nama_obat }} - {{ $obat->kemasan }} (Rp {{ number_format($obat->harga, 0, ',', '.') }})
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Pilih satu atau lebih obat yang diresepkan</small>
                                @error('obat_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <small class="text-muted d-block">Total Harga Obat: <span id="totalObat">Rp 0</span></small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan
                                </button>
                                <a href="{{ route('periksa.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            // Set default datetime to now
            var now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('tgl_periksa').value = now.toISOString().slice(0,16);

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                }
            });

            // Handle medicine price calculation
            var $obat = $('#obat_id');
            var $biayaPeriksa = $('#biaya_periksa');
            var prices = $obat.data('prices');
            var $form = $('form');

            function updateTotals() {
                var totalObat = 0;
                $obat.val().forEach(function(id) {
                    totalObat += prices[id];
                });

                var biayaPeriksa = parseInt($biayaPeriksa.val()) || 0;
                var totalBiaya = totalObat + biayaPeriksa;

                $('#totalObat').text('Rp ' + totalObat.toLocaleString('id-ID'));
                $('#totalBiaya').text('Rp ' + totalBiaya.toLocaleString('id-ID'));
            }

            $obat.on('change', updateTotals);
            $biayaPeriksa.on('input', updateTotals);
            
            // Initial calculation
            updateTotals();

            // Form validation
            $form.on('submit', function(e) {
                var isValid = true;
                var errors = [];

                // Validate pasien
                if (!$('#id_pasien').val()) {
                    isValid = false;
                    errors.push('Pasien harus dipilih');
                    $('#id_pasien').addClass('is-invalid');
                }

                // Validate tanggal periksa
                var tglPeriksa = new Date($('#tgl_periksa').val());
                var today = new Date();
                if (!$('#tgl_periksa').val()) {
                    isValid = false;
                    errors.push('Tanggal periksa harus diisi');
                    $('#tgl_periksa').addClass('is-invalid');
                } else if (tglPeriksa > today) {
                    isValid = false;
                    errors.push('Tanggal periksa tidak boleh lebih dari hari ini');
                    $('#tgl_periksa').addClass('is-invalid');
                }

                // Validate catatan
                var catatan = $('#catatan').val().trim();
                if (!catatan) {
                    isValid = false;
                    errors.push('Catatan harus diisi');
                    $('#catatan').addClass('is-invalid');
                } else if (catatan.length < 10) {
                    isValid = false;
                    errors.push('Catatan minimal 10 karakter');
                    $('#catatan').addClass('is-invalid');
                }

                // Validate biaya periksa
                var biayaPeriksa = parseInt($('#biaya_periksa').val());
                if (!biayaPeriksa) {
                    isValid = false;
                    errors.push('Biaya periksa harus diisi');
                    $('#biaya_periksa').addClass('is-invalid');
                } else if (biayaPeriksa < 50000) {
                    isValid = false;
                    errors.push('Biaya periksa minimal Rp 50.000');
                    $('#biaya_periksa').addClass('is-invalid');
                }

                // Validate obat
                if (!$('#obat_id').val() || $('#obat_id').val().length === 0) {
                    isValid = false;
                    errors.push('Minimal satu obat harus dipilih');
                    $('#obat_id').addClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    // Show error messages
                    toastr.error(errors.join('<br>'));
                    return false;
                }

                // Show loading state
                $(this).find('button[type="submit"]')
                    .attr('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
            });

            // Clear invalid state on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endpush