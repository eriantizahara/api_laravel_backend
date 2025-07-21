<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan Tiket</title>
    <style>
        /* Styling dasar halaman */
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        /* Styling tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 20px;">
        {{-- Logo bisa diaktifkan jika file tersedia di direktori publik --}}
        {{-- <img src="{{ public_path('images/logo/adventure-park1.png') }}" alt="Logo" style="width: 100px; height: auto;"> --}}

        <!-- Judul dan kontak -->
        <h1>Lawang Adventure Park</h1>
        <p>Jl. Liburan No.123, Kota Bahagia, Provinsi Bahagia</p>
        <p>Telepon: (021) 12345678 | Email: wisata@gmail.com</p>
    </div>

    <!-- Judul laporan -->
    <h2 style="margin-bottom: 30px; margin-top: 30px;">Laporan Pemesanan Tiket</h2>

    <!-- Rentang tanggal yang difilter -->
    <p style="text-align: left; margin-bottom: 10px;">
        Tanggal: {{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d-m-Y') }} s.d.
        {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d-m-Y') }}
    </p>

    <!-- Tabel utama pemesanan -->
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Admin</th>
                <th>Customer</th>
                <th>Tanggal Pemesanan</th>
                <th>Tanggal Kunjungan</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp <!-- Inisialisasi total keseluruhan -->
            @foreach ($pemesanans as $pemesanan)
                <tr>
                    <td class="text-center">{{ $pemesanan->kode_pemesanan }}</td>
                    <td>{{ $pemesanan->user->name }}</td>
                    <td>{{ $pemesanan->customer->namacustomer }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pemesanan->tanggal_kunjungan)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ ucfirst($pemesanan->status) }}</td>
                    <td>Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                </tr>

                <!-- Tabel detail wahana dalam satu pemesanan -->
                <tr>
                    <td colspan="7">
                        <strong>Detail:</strong>
                        <ul>
                            @foreach ($pemesanan->detailPemesanan as $detail)
                                <li>
                                    {{ $detail->wahana->nama_wahana }} - {{ $detail->jumlah }} tiket
                                    (Rp{{ number_format($detail->subtotal, 0, ',', '.') }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>

                @php $grandTotal += $pemesanan->total_harga; @endphp <!-- Akumulasi total harga -->
            @endforeach
        </tbody>

        <!-- Footer tabel dengan total keseluruhan -->
        <tfoot>
            <tr>
                <th colspan="6">Grand Total</th>
                <th>Rp{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
