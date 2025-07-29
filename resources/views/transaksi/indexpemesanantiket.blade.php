@extends('layouts.dashboard')

@section('page-heading')
    <h2 class="text-3xl font-bold flex items-center gap-2">
        Data Pemesanan Tiket
    </h2>
@endsection

@section('content')
    <div class="col-md-12 mb-4">
        <a href="{{ route('pemesanantikets.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus-circle"></i> Tambah Pemesanan
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Kode Pemesanan</th>
                {{-- <th>Admin</th> --}}
                <th>Customer</th>
                <th>Wahana</th>
                <th>Tanggal Pemesanan</th>
                <th>Harga Tiket</th>
                <th>Tanggal Kunjungan</th>
                <th>Jumlah Tiket</th>
                <th>Total Harga</th>
                <th>Bukti Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemesanans as $pemesanan)
                <tr>
                    <td class="text-center">{{ $pemesanan->id }}</td>
                    <td class="text-center">{{ $pemesanan->kode_pemesanan }}</td>

                    {{-- Admin --}}
                    {{-- <td class="text-center">
                        {{ $pemesanan->user?->status === 'admin' ? $pemesanan->user->name : '-' }}
                    </td> --}}

                    {{-- Customer --}}
                    <td class="text-center">
                        {{ $pemesanan->user?->status === 'customer' ? $pemesanan->user->name : '-' }}
                    </td>

                    {{-- Wahana --}}
                    <td>
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->wahana->nama_wahana ?? '-' }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Tanggal Pemesanan --}}
                    <td class="text-center">{{ $pemesanan->tanggal_pemesanan }}</td>

                    {{-- Harga Tiket --}}
                    <td class="text-center">
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>Rp{{ number_format($detail->harga ?? 0, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Tanggal kunjungan --}}
                    <td class="text-center">{{ $pemesanan->tanggal_kunjungan }}</td>

                    {{-- Jumlah Tiket --}}
                    <td class="text-center">
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->jumlah }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Total Harga --}}
                    <td class="text-center">
                        Rp{{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}
                    </td>

                    {{-- Bukti Pembayaran --}}
                    <td class="text-center">
                        @if ($pemesanan->bukti_pembayaran)
                            <a href="{{ asset('storage/' . $pemesanan->bukti_pembayaran) }}" target="_blank"
                                class="btn btn-sm btn-info">
                                <i class="fa fa-eye"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        <span
                            class="badge 
                            @if ($pemesanan->status === 'pending') bg-warning
                            @elseif ($pemesanan->status === 'selesai') bg-success
                            @elseif ($pemesanan->status === 'batal') bg-danger @endif">
                            {{ ucfirst($pemesanan->status) }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center">
                        <a href="{{ route('pemesanantikets.faktur.pdf', $pemesanan->id) }}" class="btn btn-sm btn-danger"
                            target="_blank">
                            <i class="fa fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('pemesanantikets.edit', $pemesanan->id) }}" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i>
                        </a>

                        {{-- Form untuk hapus data wahana --}}
                        <form action="{{ route('pemesanantikets.destroy', $pemesanan->id) }}" method="POST"
                            class="form-hapus d-inline">

                            {{-- Token CSRF untuk keamanan form --}}
                            @csrf

                            {{-- Method spoofing karena HTML hanya mendukung GET dan POST --}}
                            @method('DELETE')

                            {{-- Tombol submit yang akan kita intercept dengan SweetAlert --}}
                            <button type="submit" class="btn btn-danger btn-sm btn-konfirmasi-hapus">
                                <i class="fa fa-trash"></i> {{-- Icon tempat sampah --}}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    {{-- Load library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil semua form yang memiliki class 'form-hapus'
            const forms = document.querySelectorAll('.form-hapus');

            // Loop tiap form dan pasang event saat submit
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Cegah form langsung dikirim
                    e.preventDefault();

                    // Tampilkan konfirmasi SweetAlert2
                    Swal.fire({
                        title: 'Yakin ingin menghapus data ini?', // Judul popup
                        text: "Data yang sudah dihapus tidak dapat dikembalikan!", // Pesan
                        icon: 'warning', // Ikon peringatan
                        showCancelButton: true, // Tampilkan tombol batal
                        confirmButtonColor: '#d33', // Warna tombol 'Ya'
                        cancelButtonColor: '#3085d6', // Warna tombol 'Batal'
                        confirmButtonText: 'Ya, Hapus!', // Teks tombol konfirmasi
                        cancelButtonText: 'Batal' // Teks tombol batal
                    }).then((result) => {
                        // Jika pengguna klik tombol konfirmasi
                        if (result.isConfirmed) {
                            form.submit(); // Submit form secara manual
                        }
                    });
                });
            });
        });
    </script>

    {{-- Notifikasi sukses jika ada session flash --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
@endpush
