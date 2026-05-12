<!DOCTYPE html>
<html>

<head>
    <title>Print Transaksi</title>
    <style>
        @media print {
            @page {
                size: 58mm 80mm;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 58mm;
            height: 80mm;
            padding: 2mm;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 1mm 0;
        }

        p {
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="receipt">
        <div class="center">
            <strong>LAUNDRY CAMP</strong><br>
            Jl. Bantan, Gg. Cahaya, Senggoro<br>
            Telp/WhatsApp: 082284392025
        </div>

        <div class="line"></div>

        <div class="center">
            LAYANAN
            {{ $transaksi->price->nama ?? 'Nama Tidak Tersedia' }} -
            {{ $transaksi->price->jenis ?? 'Jenis Tidak Tersedia' }}
        </div>
        <br>

        <p>
            Karyawan: {{ $transaksi->karyawan->name }}<br>
        </p>

        <div class="line"></div>

        <p>
            Invoice: {{ $transaksi->invoice }}<br>
            Tanggal: {{ $transaksi->tgl_transaksi }}<br>
            Customer: {{ $transaksi->customer }}<br>
            Berat: {{ $transaksi->kg }} kg<br>
            Lembar Pakaian: {{ $transaksi->jumlah_lembar_baju }} pcs<br>
            Jenis Pewangi: {{ $transaksi->jenis_pewangi }}<br>
            Total: Rp {{ number_format($total_harga, 0, ',', '.') }}<br>
            Diskon: Rp {{ number_format($transaksi->disc, 0, ',', '.') }}<br>
            Harga Akhir: Rp {{ number_format($transaksi->harga_akhir, 0, ',', '.') }}<br>
            Pembayaran: {{ $transaksi->jenis_pembayaran }} ({{ $transaksi->info_pembayaran }})<br>
            Catatan: {{ $transaksi->catatan_admin }}
        </p>

        <div class="line"></div>

        <div class="center">
            Terima kasih!<br>
            Laundry Selesai
            {{ is_numeric($transaksi->hari) ? $transaksi->hari . ' hari' : $transaksi->hari }}
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <a href="{{ route('pelayanan.index') }}">
            <button>Kembali ke Transaksi</button>
        </a>
    </div>
</body>

</html>
