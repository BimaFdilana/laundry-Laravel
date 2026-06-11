<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivitasKaryawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivitas_karyawans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id')->nullable();
            $table->unsignedBigInteger('transaksi_satuan_id')->nullable();
            $table->unsignedBigInteger('karyawan_id');
            $table->enum('jenis_aktivitas', ['cuci', 'gosok', 'packing']);
            $table->integer('jumlah_item')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
            $table->foreign('transaksi_satuan_id')->references('id')->on('transaksi_satuans')->onDelete('cascade');
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivitas_karyawans');
    }
}
