@extends('layouts.dashboard')

@section('page-heading')
    <h2 class="text-3xl font-bold">Laporan Pemesanan Tiket Wahana</h2>
@endsection

@section('content')
    <div class="card p-4 bg-white mb-4">
        <form action="{{ route('laporan.index') }}" method="GET" class="row">
            <div class="col-md-4">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 w-90">
                    <i class="bi bi-search"></i> Tampilkan</button>

                @if (request('tanggal_awal') && request('tanggal_akhir'))
                    <a href="{{ route('laporan.cetak', [
                        'tanggal_awal' => request('tanggal_awal'),
                        'tanggal_akhir' => request('tanggal_akhir'),
                    ]) }}"
                        target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak
                    </a>
                @endif

            </div>
        </form>

    </div>

    @if ($pemesanans->count() > 0)
        <div class="card p-4 bg-white">
            <h5>Hasil Laporan</h5>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Tanggal Pemesanan</th>
                        <th class="text-center">Tanggal Kunjungan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($pemesanans as $pemesanan)
                        <tr>
                            <td class="text-center">{{ $pemesanan->kode_pemesanan }}</td>
                            <td>{{ $pemesanan->user->name }}</td>
                            <td class="text-center">{{ $pemesanan->tanggal_pemesanan }}</td>
                            <td class="text-center">{{ $pemesanan->tanggal_kunjungan }}</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-{{ $pemesanan->status == 'selesai' ? 'success' : ($pemesanan->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pemesanan->status) }}
                                </span>
                            </td>
                            <td>Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <strong>Detail Wahana:</strong>
                                <ul>
                                    @foreach ($pemesanan->detailPemesanan as $detail)
                                        <li>{{ $detail->wahana->nama_wahana }} - {{ $detail->jumlah }} tiket @
                                            Rp{{ number_format($detail->harga, 0, ',', '.') }} =
                                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @php $grandTotal += $pemesanan->total_harga; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total:</th>
                        <th>Rp{{ number_format($grandTotal, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Tidak ada data pemesanan untuk rentang tanggal yang dipilih.
        </div>
    @endif
@endsection
