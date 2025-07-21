@extends('layouts.dashboard')

@section('page-heading')
    <h2 class="text-3xl font-bold">Form Pemesanan Tiket</h2>
@endsection

@section('content')
    <div class="card p-4 bg-white">
        <form action="{{ route('pemesanantikets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informasi Umum -->
            <div class="row">
                <div class="col-md-6">
                    <label>Kode Pemesanan</label>
                    <input type="text" name="kode_pemesanan" class="form-control" value="{{ $kode_pemesanan }}" readonly>
                </div>

                {{-- <div class="col-md-6">
                <label>Admin</label>
                <input type="text" name="admin_name" value="{{ auth()->user()->name }}" class="form-control" readonly>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </div> --}}

                <!-- Admin -->
                <div class="col-md-6">
                    <label>Admin</label>
                    @auth
                        {{-- Menampilkan nama admin yang sedang login --}}
                        <input type="text" name="user_name" value="{{ auth()->user()->name }}" class="form-control" readonly>

                        {{-- Menyimpan ID admin (bukan nama) untuk disimpan di kolom user_id --}}
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    @else
                        <input type="text" class="form-control is-invalid" value="(Belum login)" readonly>
                    @endauth
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Customer</label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">Pilih Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->namacustomer }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" class="form-control" required>
                </div>
            </div>

            <!-- Tabel Dinamis untuk Wahana -->
            <hr>
            <h5 class="mt-4">Detail Wahana</h5>
            <table class="table table-bordered" id="wahana-table">
                <thead>
                    <tr>
                        <th>Wahana</th>
                        <th>Harga Tiket</th>
                        <th>Jumlah Tiket</th>
                        <th>Sub Total</th>
                        <th><button type="button" class="btn btn-sm btn-success" id="addRow">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="wahana_id[]" class="form-control wahana-select" required>
                                <option value="">Pilih</option>
                                @foreach ($wahanas as $wahana)
                                    <option value="{{ $wahana->id }}" data-harga="{{ $wahana->harga_tiket }}">
                                        {{ $wahana->nama_wahana }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control harga" name="harga[]" readonly></td>
                        <td><input type="number" class="form-control jumlah" name="jumlah[]" min="1" value="1">
                        </td>
                        <td><input type="text" class="form-control subtotal" name="subtotal[]" readonly></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeRow">-</button></td>
                    </tr>
                </tbody>
            </table>

            {{-- <!-- Total Harga -->
            <div class="form-group">
                <label>Total Harga</label>
                <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>
            </div>

            <!-- Tombol Lihat Barcode -->
            <button type="button" class="btn btn-secondary mt-2 mb-2" data-bs-toggle="modal" data-bs-target="#barcodeModal">
                Lihat Barcode Pembayaran
            </button> --}}

            <!-- Total Harga dan Barcode Button Sejajar -->
            <div class="form-group mb-3">
                <label for="total_harga">Total Harga</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>

                    <!-- Tombol Barcode (bisa juga diganti icon jika mau lebih kecil) -->
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#barcodeModal">
                        <i class="bi bi-upc-scan"></i> Barcode
                    </button>
                </div>
            </div>


            <!-- Upload Bukti -->
            <div class="form-group">
                <label>Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" class="form-control">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Batal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Pemesanan</button>
        </form>
    </div>

    <!-- Modal Barcode -->
    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeModalLabel">Barcode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('images/barcode_dana.png') }}" alt="Barcode DANA" class="img-fluid"
                        style="max-width: 300px;">
                    <p class="mt-2">Silakan scan barcode ini untuk melakukan pembayaran.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tambah baris
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.querySelector('#wahana-table tbody');
            const firstRow = tableBody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'INPUT') input.value = '';
            });
            tableBody.appendChild(newRow);
        });

        // Hapus baris
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                const row = e.target.closest('tr');
                const rowCount = document.querySelectorAll('#wahana-table tbody tr').length;
                if (rowCount > 1) row.remove();
                hitungTotal();
            }
        });

        // Harga dan subtotal otomatis
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('wahana-select')) {
                const harga = e.target.selectedOptions[0].dataset.harga;
                const row = e.target.closest('tr');
                row.querySelector('.harga').value = harga;
                const jumlah = row.querySelector('.jumlah').value || 1;
                row.querySelector('.subtotal').value = (harga * jumlah).toFixed(2);
                hitungTotal();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah')) {
                const row = e.target.closest('tr');
                const harga = row.querySelector('.harga').value;
                const jumlah = e.target.value;
                row.querySelector('.subtotal').value = (harga * jumlah).toFixed(2);
                hitungTotal();
            }
        });

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(sub => {
                total += parseFloat(sub.value) || 0;
            });
            document.getElementById('total_harga').value = total.toFixed(2);
        }
    </script>
@endpush
