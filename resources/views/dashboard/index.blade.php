@extends('layout.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary-gradient me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_pasien_hari_ini'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Pasien Hari Ini</p>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> 
                            Total: {{ $stats['total_pasien'] ?? 0 }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success-gradient me-3">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_pemeriksaan_hari_ini'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Pemeriksaan Hari Ini</p>
                        <small class="text-info">
                            <i class="fas fa-calendar"></i> 
                            Aktif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning-gradient me-3">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">Rp {{ number_format($stats['pendapatan_hari_ini'] ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-muted mb-0">Pendapatan Hari Ini</p>
                        <small class="text-warning">
                            <i class="fas fa-chart-line"></i> 
                            Revenue
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info-gradient me-3">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_dokter'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Total Dokter</p>
                        <small class="text-primary">
                            <i class="fas fa-hospital"></i> 
                            {{ $stats['total_poli'] ?? 0 }} Poli
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Kunjungan Pasien (7 Hari Terakhir)</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-period="weekly">Mingguan</button>
                        <button type="button" class="btn btn-outline-primary" data-period="monthly">Bulanan</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="visitorsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Antrian Per Poli Hari Ini</h5>
            </div>
            <div class="card-body">
                @if(isset($antrianPerPoli) && $antrianPerPoli->count() > 0)
                    @foreach($antrianPerPoli as $antrian)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $antrian->nama_poli }}</h6>
                            <small class="text-muted">Poli {{ $antrian->nama_poli }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill">{{ $antrian->jumlah_antrian }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>Belum ada antrian hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Dokter</th>
                                    <th>No. Antrian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>
                                        <small>{{ $activity->created_at->format('H:i') }}</small><br>
                                        <small class="text-muted">{{ $activity->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $activity->pasien->nama ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $activity->pasien->no_rm ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $activity->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}</td>
                                    <td>{{ $activity->jadwalPeriksa->dokter->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $activity->no_antrian }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $status = $activity->status ?? 'Menunggu';
                                            $badgeClass = match($status) {
                                                'Selesai' => 'bg-success',
                                                'Sedang Diperiksa' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('pasien.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus mb-2"></i><br>
                            Tambah Pasien
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('dokter.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-user-md mb-2"></i><br>
                            Tambah Dokter
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('poli.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-hospital mb-2"></i><br>
                            Tambah Poli
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('obat.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-pills mb-2"></i><br>
                            Tambah Obat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .chart-container canvas {
        max-height: 300px !important;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Visitors Chart
    const ctx = document.getElementById('visitorsChart').getContext('2d');
    
    const chartData = @json($kunjunganMingguan ?? []);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.hari || 'N/A'),
            datasets: [{
                label: 'Kunjungan',
                data: chartData.map(item => item.jumlah || 0),
                borderColor: 'rgb(13, 110, 253)',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(13, 110, 253)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6c757d'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6c757d'
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });

    // Period buttons
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('[data-period]').forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you would typically fetch new data based on the period
            // For now, we'll just show a placeholder
            console.log('Period changed to:', this.dataset.period);
        });
    });

    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        fetch('/admin/api/stats')
            .then(response => response.json())
            .then(data => {
                // Update stats if needed
                console.log('Stats updated:', data);
            })
            .catch(error => console.error('Error fetching stats:', error));
    }, 30000);
});
</script>
@endpush