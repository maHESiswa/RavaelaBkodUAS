@extends('layout.admin')

@section('title', 'Edit Dokter')
@section('page-title', 'Edit Dokter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dokter.index') }}">Dokter</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dokter.show', $dokter) }}">{{ $dokter->nama }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Edit Dokter</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dokter.update', $dokter) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $dokter->nama) }}"
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
                                        <option value="{{ $poli->id }}" 
                                                {{ old('id_poli', $dokter->id_poli) == $poli->id ? 'selected' : '' }}>
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
                                  required>{{ old('alamat', $dokter->alamat) }}</textarea>
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
                               value="{{ old('no_hp', $dokter->no_hp) }}"
                               placeholder="Contoh: 081234567890"
                               required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: +62 atau 08 diikuti 8-12 digit</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dokter.show', $dokter) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-list me-2"></i>Daftar Dokter
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Dokter
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
                        <p><strong>Nama:</strong> {{ $dokter->nama }}</p>
                        <p><strong>Poli:</strong> {{ $dokter->poli->nama_poli ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Jadwal:</strong> {{ $dokter->jadwalPeriksas->count() }} jadwal</p>
                        <p><strong>Bergabung:</strong> {{ $dokter->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                
                @if($dokter->jadwalPeriksas->count() > 0)
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Dokter ini memiliki {{ $dokter->jadwalPeriksas->count() }} jadwal praktek. 
                    Perubahan poli akan mempengaruhi jadwal yang ada.
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
});
</script>
@endpush