@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h3 class="mb-4">Edit Pemesanan Tiket</h3>

        <form action="{{ route('pemesanantikets.update', $pemesanan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="card mb-4">
                <div class="card-body row g-3">

                    <!-- Kode Pemesanan -->
                    <div class="col-md-6">
                        <label>Kode Pemesanan</label>
                        <input type="text" class="form-control" value="{{ $pemesanan->kode_pemesanan }}" readonly>
                    </div>

                    <!-- Admin -->
                    <div class="col-md-6">
                        <label>Admin</label>
                        @auth
                            <input type="text" name="user_name" value="{{ auth()->user()->name }}" class="form-control"
                                readonly>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        @else
                            <input type="text" class="form-control is-invalid" value="(Belum login)" readonly>
                        @endauth
                    </div>

                    <!-- Customer -->
                    <div class="col-md-6">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $pemesanan->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->namacustomer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div class="col-md-6">
                        <label>Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" class="form-control"
                            value="{{ $pemesanan->tanggal_kunjungan }}">
                    </div>
                </div>
            </div>

            <!-- Detail Wahana -->
            <div class="card mb-4">
                <div class="card-body">
                    <label>Detail Wahana</label>
                    <table class="table table-bordered mt-2" id="wahana-table">
                        <thead>
                            <tr class="text-center">
                                <th>Wahana</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>
                                    <button type="button" class="btn btn-success btn-sm" id="addRow">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <tr>
                                    <td>
                                        <select name="wahana_id[]" class="form-control" onchange="updateWahana(this)">
                                            @foreach ($wahanas as $wahana)
                                                <option value="{{ $wahana->id }}" data-harga="{{ $wahana->harga }}"
                                                    {{ $detail->wahana_id == $wahana->id ? 'selected' : '' }}>
                                                    {{ $wahana->nama_wahana }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="harga[]" class="form-control harga"
                                            value="{{ $detail->harga }}" readonly></td>
                                    <td><input type="number" name="jumlah[]" class="form-control jumlah"
                                            value="{{ $detail->jumlah }}" oninput="updateSubtotal(this)"></td>
                                    <td><input type="number" name="subtotal[]" class="form-control subtotal"
                                            value="{{ $detail->subtotal }}" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeRow(this)">×</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total & Barcode -->
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center gap-2">
                    <label class="mb-0">Total Harga</label>
                    <input type="text" name="total_harga" id="total_harga" class="form-control"
                        value="{{ $pemesanan->total_harga }}" readonly style="max-width: 630px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#barcodeModal">
                        <i class="bi bi-upc-scan"></i>
                    </button>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="card mb-4">
                <div class="card-body">
                    <label>Bukti Pembayaran</label>
                    @if ($pemesanan->bukti_pembayaran)
                        <p>
                            <a href="{{ asset('storage/' . $pemesanan->bukti_pembayaran) }}"
                                class="btn btn-outline-primary btn-sm" target="_blank">
                                Lihat File
                            </a>
                        </p>
                    @endif
                    <input type="file" name="bukti_pembayaran" class="form-control">
                </div>
            </div>

            <!-- Status -->
            <div class="card mb-4">
                <div class="card-body col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-control" style="max-width: 630px;">
                        @foreach (['pending', 'selesai', 'batal'] as $status)
                            <option value="{{ $status }}" {{ $pemesanan->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tombol -->
            <div class="mb-5">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('pemesanantikets.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <!-- Modal Barcode -->
    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content p-4 text-center">
                <h5>Barcode Pembayaran</h5>
                <img src="{{ asset('storage/barcode/' . $pemesanan->kode_pemesanan . '.png') }}" alt="Barcode"
                    class="img-fluid">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateSubtotal(el) {
            const row = el.closest('tr');
            const harga = parseFloat(row.querySelector('.harga')?.value) || 0;
            const jumlah = parseInt(row.querySelector('.jumlah')?.value) || 0;
            const subtotal = harga * jumlah;
            row.querySelector('.subtotal').value = subtotal;
            updateTotal();
        }

        function updateWahana(selectEl) {
            const row = selectEl.closest('tr');
            const selected = selectEl.options[selectEl.selectedIndex];
            const harga = selected.getAttribute('data-harga') || 0;
            row.querySelector('.harga').value = harga;
            updateSubtotal(selectEl);
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_harga').value = total;
        }

        function removeRow(button) {
            const tableBody = document.querySelector('#wahana-table tbody');
            const row = button.closest('tr');
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 1) {
                row.remove();
                updateTotal();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#wahana-table tbody');

            // Inisialisasi subtotal saat pertama kali
            tableBody.querySelectorAll('tr').forEach(row => {
                updateSubtotal(row.querySelector('.jumlah'));
            });

            // Tombol tambah baris
            document.getElementById('addRow').addEventListener('click', function() {
                const newRow = tableBody.rows[0].cloneNode(true);

                newRow.querySelectorAll('input').forEach(input => {
                    if (input.classList.contains('jumlah')) {
                        input.value = 1;
                    } else if (input.classList.contains('subtotal')) {
                        input.value = 0;
                    } else if (!input.classList.contains('harga')) {
                        input.value = '';
                    }
                });

                const select = newRow.querySelector('select');
                select.selectedIndex = 0;
                const defaultHarga = select.options[0].getAttribute('data-harga') || 0;
                newRow.querySelector('.harga').value = defaultHarga;

                newRow.querySelector('.jumlah').setAttribute('oninput', 'updateSubtotal(this)');
                newRow.querySelector('select').setAttribute('onchange', 'updateWahana(this)');
                newRow.querySelector('.btn-danger').setAttribute('onclick', 'removeRow(this)');

                tableBody.appendChild(newRow);
                updateTotal();
            });
        });
    </script>
@endsection
