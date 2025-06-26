@extends('layout.admin')

@section('title', 'Tambah Pasien')
@section('page-title', 'Tambah Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Pasien</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Tambah Pasien</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pasien.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap pasien"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_ktp" class="form-label">Nomor KTP <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('no_ktp') is-invalid @enderror" 
                                       id="no_ktp" 
                                       name="no_ktp" 
                                       value="{{ old('no_ktp') }}"
                                       placeholder="16 digit nomor KTP"
                                       maxlength="16"
                                       required>
                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="ktp-count">0</span>/16 digit
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" 
                                  name="alamat" 
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap pasien"
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
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
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="no_rm" 
                                       name="no_rm" 
                                       value="{{ old('no_rm') }}"
                                       placeholder="Auto-generated"
                                       readonly>
                                <div class="form-text">Nomor RM akan dibuat otomatis dengan format: RM-YYYYMMDD-XXX</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Pasien
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
                    <li>Nomor KTP harus unik dan terdiri dari 16 digit angka</li>
                    <li>Nomor HP harus dalam format Indonesia (+62 atau 08)</li>
                    <li>Nomor Rekam Medis akan dibuat otomatis dengan format RM-YYYYMMDD-XXX</li>
                    <li>Semua field yang bertanda (*) wajib diisi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ktpInput = document.getElementById('no_ktp');
    const ktpCount = document.getElementById('ktp-count');
    const phoneInput = document.getElementById('no_hp');
    
    // KTP validation and counter
    ktpInput.addEventListener('input', function(e) {
        // Only allow numbers
        let value = this.value.replace(/\D/g, '');
        this.value = value;
        
        // Update counter
        ktpCount.textContent = value.length;
        
        // Visual feedback
        if (value.length === 16) {
            ktpCount.parentElement.classList.add('text-success');
            ktpCount.parentElement.classList.remove('text-warning', 'text-danger');
        } else if (value.length > 10) {
            ktpCount.parentElement.classList.add('text-warning');
            ktpCount.parentElement.classList.remove('text-success', 'text-danger');
        } else {
            ktpCount.parentElement.classList.add('text-danger');
            ktpCount.parentElement.classList.remove('text-success', 'text-warning');
        }
    });
    
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
        const noKtp = ktpInput.value.trim();
        const noHp = phoneInput.value.trim();
        
        if (!nama || !alamat || !noKtp || !noHp) {
            e.preventDefault();
            alert('Semua field wajib diisi!');
            return false;
        }
        
        if (noKtp.length !== 16) {
            e.preventDefault();
            ktpInput.classList.add('is-invalid');
            ktpInput.focus();
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