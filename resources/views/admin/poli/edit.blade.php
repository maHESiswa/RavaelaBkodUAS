@extends('layout.admin')

@section('title', 'Edit Poli')
@section('page-title', 'Edit Poli')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('poli.index') }}">Poli</a></li>
    <li class="breadcrumb-item"><a href="{{ route('poli.show', $poli) }}">{{ $poli->nama_poli }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Form Edit Poli</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('poli.update', $poli) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama_poli" class="form-label">Nama Poli <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_poli') is-invalid @enderror" 
                               id="nama_poli" 
                               name="nama_poli" 
                               value="{{ old('nama_poli', $poli->nama_poli) }}"
                               placeholder="Masukkan nama poli (maksimal 25 karakter)"
                               maxlength="25"
                               required>
                        @error('nama_poli')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <span id="char-count">{{ strlen($poli->nama_poli) }}</span>/25 karakter
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" 
                                  name="keterangan" 
                                  rows="4"
                                  placeholder="Masukkan keterangan atau deskripsi poli (opsional)">{{ old('keterangan', $poli->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('poli.show', $poli) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <a href="{{ route('poli.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-list me-2"></i>Daftar Poli
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Poli
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
                        <p><strong>Nama Poli:</strong> {{ $poli->nama_poli }}</p>
                        <p><strong>Jumlah Dokter:</strong> {{ $poli->dokters->count() }} dokter</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dibuat:</strong> {{ $poli->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Terakhir Update:</strong> {{ $poli->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($poli->dokters->count() > 0)
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Poli ini memiliki {{ $poli->dokters->count() }} dokter yang ditugaskan. 
                    Perubahan nama poli akan mempengaruhi data terkait.
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
    const namaPoliInput = document.getElementById('nama_poli');
    const charCount = document.getElementById('char-count');
    
    // Character counter
    namaPoliInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 20) {
            charCount.parentElement.classList.add('text-warning');
        } else {
            charCount.parentElement.classList.remove('text-warning');
        }
        
        if (length >= 25) {
            charCount.parentElement.classList.add('text-danger');
            charCount.parentElement.classList.remove('text-warning');
        } else {
            charCount.parentElement.classList.remove('text-danger');
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const namaPoli = namaPoliInput.value.trim();
        
        if (namaPoli.length === 0) {
            e.preventDefault();
            namaPoliInput.classList.add('is-invalid');
            namaPoliInput.focus();
            return false;
        }
        
        if (namaPoli.length > 25) {
            e.preventDefault();
            namaPoliInput.classList.add('is-invalid');
            namaPoliInput.focus();
            return false;
        }
    });
});
</script>
@endpush