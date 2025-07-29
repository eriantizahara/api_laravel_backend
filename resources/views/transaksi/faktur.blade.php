<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Masuk</title>
    <style>
        @media print {
            @page {
                size: auto; /* Ukuran kertas mengikuti konten */
                margin: 0;   /* Hilangkan margin default */
            }

            body {
                margin: 0;
                padding: 10px;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            width: 280px; /* Lebar struk (sekitar 58mm/80mm printer) */
            margin: 0 auto;
            padding: 10px;
        }

        .text-center { text-align: center; }
        .border-top { border-top: 1px dashed #000; margin-top: 5px; margin-bottom: 5px; }
        .ticket-info td { padding: 2px 0; }
        .footer { font-size: 10px; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="text-center">
        <h3 style="margin:0;">TIKET MASUK WAHANA</h3>
        <p style="margin:0;">Lawang Adventure Park</p>
        <p style="margin:0;">Jl. Sukses No.1, Kabupaten Agam, Provinsi Sumatera Barat</p>
        <div class="border-top"></div>
    </div>

    <table class="ticket-info" width="100%">
        <tr>
            <td><strong>Kode</strong></td>
            <td>: {{ $pemesanan->kode_pemesanan }}</td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $pemesanan->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kunjungan</strong></td>
            <td>: {{ $pemesanan->tanggal_kunjungan }}</td>
        </tr>
        <tr>
            <td><strong>Total Tiket</strong></td>
            <td>: {{ $pemesanan->total_tiket }}</td>
        </tr>
    </table>

    <div class="border-top"></div>
    <p><strong>Rincian Wahana:</strong></p>
    <table width="100%">
        @foreach($pemesanan->detailPemesanan as $item)
        <tr>
            <td>{{ $item->wahana->nama_wahana }}</td>
            <td style="text-align:right;">x{{ $item->jumlah }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-top"></div>
    <table width="100%">
        <tr>
            <td><strong>Total Bayar</strong></td>
            <td style="text-align:right;">Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td style="text-align:right;">{{ ucfirst($pemesanan->status) }}</td>
        </tr>
    </table>

    <div class="border-top"></div>
    <div class="footer">
        Terima kasih atas Pemesanan Tiket Anda!<br>
        <strong>~ Selamat bersenang-senang ~</strong>
    </div>

</body>
</html>



