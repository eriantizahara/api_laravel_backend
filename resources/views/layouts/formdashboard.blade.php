@extends('layouts.dashboard')

@section('page-heading')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="text-3xl font-bold flex items-center gap-2">
                Selamat Datang <span>👋</span>
            </h1>

            <p class="mt-2 text-gray-700">
                Hai admin {{ Auth::user()->name }}, selamat datang di dashboard Anda. Silakan gunakan menu di samping untuk mengelola data Pemesanan Tiket
                Wahana.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Statistik Kartu -->
    <div class="row mb-1 g-3">
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <div class="mb-2 text-success">
                    <i class="bi bi-person-badge-fill fs-2"></i>
                </div>
                <h6 class="mb-1 text-muted">Total User</h6>
                <h4>{{ $totalUser ?? 0 }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <div class="mb-2 text-warning">
                    <i class="bi bi-ticket-detailed-fill fs-2"></i>
                </div>
                <h6 class="mb-1 text-muted">Total Wahana</h6>
                <h4>{{ $totalWahana ?? 0 }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <div class="mb-2 text-primary">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
                <h6 class="mb-1 text-muted">Total Pemesanan</h6>
                <h4>{{ $totalPemesanan ?? 0 }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <div class="mb-2 text-danger">
                    <i class="bi bi-cash-stack fs-2"></i>
                </div>
                <h6 class="mb-1 text-muted">Total Pendapatan</h6>
                <h4>Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <!-- Ekspor & Aksi -->
    <div class="row mb-4 mt-1">
        <div class="col-md-12">
            <a href="{{ route('laporan.cetak') }}" class="text-decoration-none">
                <div
                    class="border border-danger rounded shadow-sm p-3 h-100 d-flex justify-content-center align-items-center text-center">
                    <div class="d-flex align-items-center gap-4">
                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-2"></i>
                        <div class="text-start">
                            <div class="fw-bold text-dark">Ekspor PDF</div>
                            <div class="text-muted small">Laporan lengkap</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- <div class="col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="border border-success rounded shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-4">
                        <i class="bi bi-file-earmark-excel-fill text-success fs-2"></i>
                        <div>
                            <div class="fw-bold text-dark">Ekspor Excel</div>
                            <div class="text-muted small">Data spreadsheet</div>
                        </div>
                    </div>
                </div>
            </a>
        </div> --}}
        {{-- <div class="col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="border border-primary rounded shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center gap-4 align-middle">
                        <i class="bi bi-bar-chart-line-fill text-primary fs-2"></i>
                        <div>
                            <div class="fw-bold text-dark">Statistik Detail</div>
                            <div class="text-muted small">Analisis mendalam</div>
                        </div>
                    </div>
                </div>
            </a>
        </div> --}}
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body text-center">
            <h6 class="mb-1">
                <i class="bi bi-clock text-primary me-2"></i>
                Tanggal & Waktu Hari Ini
            </h6>
            <div class="fw-bold" id="tanggal-hari-ini">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
            <div class="text-muted" id="jam-digital" style="font-size: 1.2rem;"></div>
        </div>
    </div>


    <script>
        function updateClock() {
            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0');
            const menit = now.getMinutes().toString().padStart(2, '0');
            const detik = now.getSeconds().toString().padStart(2, '0');
            const waktu = `${jam}:${menit}:${detik}`;
            document.getElementById('jam-digital').textContent = waktu;
        }

        setInterval(updateClock, 1000);
        updateClock(); // panggil sekali saat load
    </script>
@endsection
