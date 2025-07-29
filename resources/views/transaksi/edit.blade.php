@extends('layouts.dashboard')

@section('page-heading')
    <h2 class="text-3xl font-bold">Edit Data Pemesanan Tiket</h2>
@endsection

@section('content')
    <div class="card p-4 bg-white">
        <form action="{{ route('pemesanantikets.update', $pemesanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <label>Kode Pemesanan</label>
                    <input type="text" name="kode_pemesanan" class="form-control" value="{{ $pemesanan->kode_pemesanan }}"
                        readonly>
                </div>
                {{-- <div class="col-md-6">
                    <label>Admin</label>
                    <input type="text" class="form-control" value="{{ $pemesanan->admin->name ?? '-' }}" readonly>
                </div> --}}
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="user_id">Customer</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Pilih Customer</option>
                        @foreach ($users as $user)
                            @if ($user->status === 'customer')
                                <option value="{{ $user->id }}"
                                    {{ $pemesanan->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" class="form-control"
                        value="{{ $pemesanan->tanggal_kunjungan }}" required>
                </div>
            </div>

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
                    @foreach ($pemesanan->detailPemesanan as $detail)
                        <tr>
                            <td>
                                <select name="wahana_id[]" class="form-control wahana-select" required>
                                    <option value="">Pilih</option>
                                    @foreach ($wahanas as $wahana)
                                        <option value="{{ $wahana->id }}" data-harga="{{ $wahana->harga_tiket }}"
                                            {{ $detail->wahana_id == $wahana->id ? 'selected' : '' }}>
                                            {{ $wahana->nama_wahana }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control harga" name="harga[]"
                                    value="{{ $detail->harga }}" readonly></td>
                            <td><input type="number" class="form-control jumlah" name="jumlah[]"
                                    value="{{ $detail->jumlah }}" min="1"></td>
                            <td><input type="text" class="form-control subtotal" name="subtotal[]"
                                    value="{{ $detail->subtotal }}" readonly></td>
                            <td><button type="button" class="btn btn-sm btn-danger removeRow">-</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="form-group mb-3">
                <label for="total_harga">Total Harga</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="total_harga" id="total_harga" class="form-control"
                        value="{{ $pemesanan->total_harga }}" readonly>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#barcodeModal">
                        <i class="bi bi-upc-scan"></i> Barcode
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" class="form-control">
                @if ($pemesanan->bukti_pembayaran)
                    <p class="mt-2">File lama: <a href="{{ asset('storage/' . $pemesanan->bukti_pembayaran) }}"
                            target="_blank">Lihat</a></p>
                @endif
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="selesai" {{ $pemesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ $pemesanan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="text-end">
                <a href="{{ route('pemesanantikets.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

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
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.querySelector('#wahana-table tbody');
            const firstRow = tableBody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'INPUT') {
                    input.value = input.classList.contains('jumlah') ? 1 : '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });

            tableBody.appendChild(newRow);
        });

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

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah')) {
                const row = e.target.closest('tr');
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                const jumlah = parseInt(e.target.value) || 1;
                e.target.value = (jumlah < 1) ? 1 : jumlah;
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
