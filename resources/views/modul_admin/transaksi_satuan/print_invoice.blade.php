<!DOCTYPE html>
<html>

<head>
    <title>Print Transaksi Satuan</title>
    <style>
        @media print {
            @page {
                size: 58mm 120mm;
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
            height: 120mm;
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
            Telp/WA: 082284392025
        </div>

        <div class="line"></div>

        <div class="center">LAYANAN SATUAN</div>
        <br>

        <p>
            Karyawan: {{ $dataInvoice->karyawan->name }}<br>
        </p>

        <div class="line"></div>

        <p>
            Invoice: {{ $dataInvoice->invoice }}<br>
            Tanggal: {{ $dataInvoice->tgl_transaksi }}<br>
            Customer: {{ $dataInvoice->customer }}<br>
            Jenis Pewangi: {{ $dataInvoice->jenis_pewangi }}<br>
            Pembayaran: {{ $dataInvoice->jenis_pembayaran }} ({{ $dataInvoice->info_pembayaran }})
        </p>

        <div class="line"></div>
        <div class="center">Detail Barang</div>
        <div class="line"></div>

        @foreach ($dataInvoice->details as $detail)
            <p>
                {{ $detail->satuan->nama }} - {{ $detail->pcs }} pcs<br>
                Harga: Rp {{ number_format($detail->harga, 0, ',', '.') }}<br>
                Subtotal: Rp {{ number_format($detail->subtotal, 0, ',', '.') }}<br>
                Waktu: {{ is_numeric($detail->hari) ? $detail->hari . ' hari' : $detail->hari }}
            </p>
            <div class="line"></div>
        @endforeach

        <p><strong>Total: Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
        <p><strong>Diskon: Rp {{ number_format($dataInvoice->disc, 0, ',', '.') }}</strong></p>
        <p><strong>Harga Akhir: Rp {{ number_format($dataInvoice->harga_akhir, 0, ',', '.') }}</strong></p>
        <div class="center">
            Terima kasih!
        </div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <a href="{{ route('transaksi.indexsatuan') }}">
            <button>Kembali ke Invoice Transaksi Satuan</button>
        </a>
    </div>
</body>

</html>
