<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisPewangiToTransaksisAndTransaksiSatuans extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('jenis_pewangi')->nullable()->after('catatan_admin');
        });

        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->string('jenis_pewangi')->nullable()->after('catatan_admin');
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('jenis_pewangi');
        });

        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->dropColumn('jenis_pewangi');
        });
    }
}
