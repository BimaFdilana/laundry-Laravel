<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReconcileTransaksisSchema extends Migration
{
    /**
     * Skema transaksis di produksi sudah punya karyawan_id dan tidak punya user_id,
     * tapi migration lama tidak pernah membuat kolom itu. Migration ini menyamakan
     * skema agar instalasi baru (dan test) identik dengan produksi.
     */
    public function up()
    {
        if (!Schema::hasColumn('transaksis', 'karyawan_id')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->unsignedBigInteger('karyawan_id')->nullable()->after('invoice');
            });
        }

        // user_id tidak dipakai controller mana pun; buat nullable agar insert tidak gagal.
        if (Schema::hasColumn('transaksis', 'user_id')) {
            DB::statement('ALTER TABLE transaksis MODIFY user_id VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        // Tidak menghapus karyawan_id: kolom ini dipakai aplikasi.
    }
}
