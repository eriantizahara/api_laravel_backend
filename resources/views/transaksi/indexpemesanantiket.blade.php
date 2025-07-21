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
                <th>Admin</th>
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
            @foreach ($pemesananTikets as $pemesanan)
                <tr>
                    <td class="text-center">{{ $pemesanan->id }}</td>
                    <td class="text-center">{{ $pemesanan->kode_pemesanan }}</td>
                    <td class="text-center">{{ $pemesanan->user->name ?? '-' }}</td>
                    <td>{{ $pemesanan->customer->namacustomer ?? '-' }}</td>

                    {{-- Menampilkan list wahana yang dipesan --}}
                    {{-- <td>
                        <ul class="list-unstyled m-0">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->wahana->nama_wahana ?? '-' }}</li>
                            @endforeach
                        </ul>
                    </td> --}}

                    {{-- Menampilkan list wahana yang dipesan --}}
                    <td>
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->wahana->nama_wahana ?? '-' }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Tanggal Pemesanan --}}
                    <td class="text-center">{{ $pemesanan->tanggal_pemesanan }}</td>

                    {{-- Menampilkan harga tiket per wahana --}}
                    {{-- <td class="text-center">
                        <ul class="list-unstyled m-0">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>Rp{{ number_format($detail->harga ?? 0, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td> --}}

                    {{-- Menampilkan harga tiket per wahana --}}
                    <td class="text-center">
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>Rp{{ number_format($detail->harga ?? 0, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Tanggal kunjungan --}}
                    <td class="text-center">{{ $pemesanan->tanggal_kunjungan }}</td>

                    {{-- Jumlah tiket per wahana --}}
                    {{-- <td class="text-center">
                        <ul class="list-unstyled m-0">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->jumlah }}</li>
                            @endforeach
                        </ul>
                    </td> --}}

                    {{-- Jumlah tiket per wahana --}}
                    <td class="text-center">
                        <ul class="list-disc list-inside">
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>{{ $detail->jumlah }}</li>
                            @endforeach
                        </ul>
                    </td>

                    {{-- Total harga semua tiket --}}
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
                        <form action="{{ route('pemesanantikets.destroy', $pemesanan->id) }}" method="POST"
                            style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus pemesanan ini?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
