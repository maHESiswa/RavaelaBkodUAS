@extends('layout.admin')

@section('title', 'Edit Pasien')
@section('page-title', 'Edit Pasien')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pasien.index') }}">Pasien</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pasien.show', $pasien) }}">{{ $pasien->nama }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Edit Pasien</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pasien.update', $pasien) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $pasien->nama) }}"
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
                                       value="{{ old('no_ktp', $pasien->no_ktp) }}"
                                       placeholder="16 digit nomor KTP"
                                       maxlength="16"
                                       required>
                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="ktp-count">{{ strlen($pasien->no_ktp) }}</span>/16 digit
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
                                  required>{{ old('alamat', $pasien->alamat) }}</textarea>
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
                                       value="{{ old('no_hp', $pasien->no_hp) }}"
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
                                       value="{{ $pasien->no_rm }}"
                                       readonly>
                                <div class="form-text">Nomor RM tidak dapat diubah</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-list me-2"></i>Daftar Pasien
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Pasien
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Info Card -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Saat Ini
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $pasien->nama }}</p>
                        <p><strong>No. RM:</strong> {{ $pasien->no_rm }}</p>
                        <p><strong>No. KTP:</strong> {{ $pasien->no_ktp }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>No. HP:</strong> {{ $pasien->no_hp }}</p>
                        <p><strong>Terdaftar:</strong> {{ $pasien->created_at->format('d/m/Y') }}</p>
                        <p><strong>Total Kunjungan:</strong> {{ $pasien->daftarPolis->count() }} kali</p>
                    </div>
                </div>
                
                @if($pasien->daftarPolis->count() > 0)
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Pasien ini memiliki {{ $pasien->daftarPolis->count() }} riwayat pendaftaran. 
                    Perubahan data akan mempengaruhi riwayat yang ada.
                </div>
                @endif
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
});
</script>
@endpush