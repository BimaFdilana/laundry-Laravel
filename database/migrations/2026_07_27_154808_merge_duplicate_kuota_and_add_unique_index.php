<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MergeDuplicateKuotaAndAddUniqueIndex extends Migration
{
    public function up()
    {
        $backup = 'kuota_laundry_backup_' . date('Ymd');

        // 1. Backup penuh sebelum merge (dipakai untuk restore manual bila perlu)
        if (!Schema::hasTable($backup)) {
            DB::statement("CREATE TABLE {$backup} AS SELECT * FROM kuota_laundry");
        }

        // 2. Merge baris ganda per (user_id, kategori): total masuk ke id terkecil, sisanya dihapus.
        //    Pakai query builder agar jalan di MySQL (produksi) maupun SQLite (test).
        $duplikat = DB::table('kuota_laundry')
            ->select('user_id', 'kategori', DB::raw('COUNT(*) as jml'), DB::raw('SUM(kuota) as total'), DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'kategori')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplikat as $row) {
            DB::table('kuota_laundry')->where('id', $row->keep_id)->update(['kuota' => $row->total]);

            DB::table('kuota_laundry')
                ->where('user_id', $row->user_id)
                ->where('kategori', $row->kategori)
                ->where('id', '!=', $row->keep_id)
                ->delete();
        }

        // 3. Cegah duplikat baru: satu baris kuota per customer per kategori
        Schema::table('kuota_laundry', function (Blueprint $table) {
            $table->unique(['user_id', 'kategori'], 'kuota_laundry_user_kategori_unique');
        });
    }

    public function down()
    {
        Schema::table('kuota_laundry', function (Blueprint $table) {
            $table->dropUnique('kuota_laundry_user_kategori_unique');
        });

        // Data hasil merge tidak dipulihkan otomatis.
        // Tabel kuota_laundry_backup_<Ymd> tetap ada untuk restore manual.
    }
}
