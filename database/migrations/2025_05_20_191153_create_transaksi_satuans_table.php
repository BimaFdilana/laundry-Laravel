<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiSatuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_satuans', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('karyawan_id');
            $table->foreignId('customer_id');
            $table->string('customer');
            $table->string('tgl_transaksi');
            $table->string('email_customer');
            $table->enum('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])->default('Antrian');
            $table->enum('status_payment', ['Pending', 'Success']);
            $table->string('disc')->nullable();
            $table->string('harga_akhir')->nullable();
            $table->enum('jenis_pembayaran', ['Tunai', 'Transfer']);
            $table->string('tgl');
            $table->string('bulan');
            $table->string('tahun');
            $table->string('catatan_admin');
            $table->string('tgl_ambil')->nullable();
            $table->string('info_pembayaran')->nullable();
            $table->boolean('is_hidden_customer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_satuans');
    }
}
