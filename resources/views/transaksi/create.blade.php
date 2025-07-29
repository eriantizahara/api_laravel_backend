@extends('layouts.dashboard')

@section('page-heading')
    <h2 class="text-3xl font-bold">Entri Data Pemesanan Tiket</h2>
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

                <div class="col-md-6">
                    <label>Admin</label>

                    @auth
                        @if (auth()->user()->status === 'admin')
                            {{-- Menampilkan nama admin yang sedang login --}}
                            <input type="text" name="user_name" value="{{ auth()->user()->name }}" class="form-control"
                                readonly>

                            {{-- Menyimpan ID admin untuk disimpan ke kolom user_id --}}
                            <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                        @else
                            {{-- User login tapi bukan admin --}}
                            <input type="text" class="form-control is-invalid" value="(Bukan Admin)" readonly>
                        @endif
                    @else
                        {{-- Tidak login --}}
                        <input type="text" class="form-control is-invalid" value="(Belum login)" readonly>
                    @endauth
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="user_id">Customer</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Pilih Customer</option>
                        @foreach ($users as $user)
                            @if ($user->status === 'customer')
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endif
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

            {{-- Tombol Simpan dan Kembali --}}
            <div class="text-end">
                <a href="{{ route('pemesanantikets.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>

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
                    <img src="{{ asset('assets/images/barcode_dana.jpg') }}" alt="Barcode DANA" class="img-fluid"
                        style="max-width: 300px;">
                    <p class="mt-2">Silakan scan barcode ini untuk melakukan pembayaran.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk menambahkan baris baru
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.querySelector('#wahana-table tbody');
            const firstRow = tableBody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);

            // Reset semua input di baris baru
            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'INPUT') {
                    input.value = (input.classList.contains('jumlah')) ? 1 : '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });

            tableBody.appendChild(newRow);
        });

        // Fungsi untuk menghapus baris
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                const row = e.target.closest('tr');
                const rowCount = document.querySelectorAll('#wahana-table tbody tr').length;
                if (rowCount > 1) {
                    row.remove();
                    hitungTotal();
                }
            }
        });

        // Saat pilihan wahana berubah, update harga dan subtotal
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('wahana-select')) {
                const selectedOption = e.target.selectedOptions[0];
                const harga = parseFloat(selectedOption.dataset.harga || 0);
                const row = e.target.closest('tr');
                const jumlah = parseInt(row.querySelector('.jumlah').value) || 1;

                row.querySelector('.harga').value = harga;
                row.querySelector('.subtotal').value = (harga * jumlah).toFixed(2);

                hitungTotal();
            }
        });

        // Saat jumlah tiket berubah, update subtotal
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah')) {
                const row = e.target.closest('tr');
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                const jumlah = parseInt(e.target.value) || 1;

                // Validasi agar jumlah minimal 1
                e.target.value = (jumlah < 1) ? 1 : jumlah;

                row.querySelector('.subtotal').value = (harga * jumlah).toFixed(2);
                hitungTotal();
            }
        });

        // Hitung total harga semua wahana
        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(sub => {
                total += parseFloat(sub.value) || 0;
            });
            document.getElementById('total_harga').value = total.toFixed(2);
        }
    </script>
@endpush
