@extends('layout.admin')

@section('title', 'Tambah Dokter')
@section('page-title', 'Tambah Dokter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dokter.index') }}">Dokter</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Tambah Dokter</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dokter.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap dokter"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_poli" class="form-label">Poli <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_poli') is-invalid @enderror" 
                                        id="id_poli" 
                                        name="id_poli" 
                                        required>
                                    <option value="">Pilih Poli</option>
                                    @foreach($polis as $poli)
                                        <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                            {{ $poli->nama_poli }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_poli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap dokter"
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="tel" 
                               class="form-control @error('no_hp') is-invalid @enderror" 
                               id="no_hp" 
                               name="no_hp" 
                               value="{{ old('no_hp') }}"
                               placeholder="Contoh: 081234567890"
                               required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: +62 atau 08 diikuti 8-12 digit</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dokter.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Dokter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Setiap dokter harus ditugaskan ke satu poli</li>
                    <li>Nomor HP harus dalam format Indonesia (+62 atau 08)</li>
                    <li>Setelah dokter dibuat, Anda dapat menambahkan jadwal praktek</li>
                    <li>Dokter tidak dapat dihapus jika memiliki jadwal praktek aktif</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('no_hp');
    
    // Phone number formatting
    phoneInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        
        // Ensure it starts with 0 or 62
        if (value.length > 0 && value[0] !== '0' && value.substring(0, 2) !== '62') {
            value = '0' + value;
        }
        
        this.value = value;
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nama = document.getElementById('nama').value.trim();
        const alamat = document.getElementById('alamat').value.trim();
        const noHp = phoneInput.value.trim();
        const idPoli = document.getElementById('id_poli').value;
        
        if (!nama || !alamat || !noHp || !idPoli) {
            e.preventDefault();
            alert('Semua field wajib diisi!');
            return false;
        }
        
        // Validate phone number
        const phoneRegex = /^(\+62|08)[0-9]{8,12}$/;
        if (!phoneRegex.test(noHp)) {
            e.preventDefault();
            phoneInput.classList.add('is-invalid');
            phoneInput.focus();
            return false;
        }
    });
});
</script>
@endpush