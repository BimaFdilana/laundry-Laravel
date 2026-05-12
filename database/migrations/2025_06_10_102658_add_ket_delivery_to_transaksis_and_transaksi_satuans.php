<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKetDeliveryToTransaksisAndTransaksiSatuans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->text('ket_delivery')->nullable()->after('jenis_pewangi'); // atau after kolom lain yang sesuai
        });

        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->text('ket_delivery')->nullable()->after('jenis_pewangi'); // sesuaikan juga posisi kolomnya
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('ket_delivery');
        });

        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->dropColumn('ket_delivery');
        });
    }
}
