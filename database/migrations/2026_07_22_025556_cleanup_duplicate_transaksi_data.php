<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateTransaksiData extends Migration
{
    public function up()
    {
        // 1. Hapus transaksis duplikat (invoice sama), keep MIN(id) per grup
        // ponytail: hanya cleanup invoice SAMA; duplikat satuan (invoice beda) perlu review manual
        DB::statement("DELETE t1 FROM transaksis t1
            INNER JOIN transaksis t2
            ON t1.invoice = t2.invoice AND t1.id > t2.id");

        // 2. Fix pemasukans dengan tanggal kosong: set dari created_at
        // Sumber: KuotaController & PurchaseRequestController create tanpa set 'tanggal'
        DB::statement("UPDATE pemasukans
            SET tanggal = DATE_FORMAT(created_at, '%d-%m-%Y')
            WHERE tanggal IS NULL OR tanggal = ''");

        // 3. Transaksi satuan duplikat (invoice beda, customer+tgl sama): TIDAK auto-hapus.
        //    43 grup perlu review manual. Lihat command: php artisan report:duplicate-satuan
    }

    public function down()
    {
        // Cleanup tidak reversible: data duplikat sudah dihapus permanen
    }
}
