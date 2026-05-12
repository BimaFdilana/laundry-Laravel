<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiSatuanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_satuan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_satuan_id')->constrained('transaksi_satuans')->onDelete('cascade');
            $table->foreignId('satuan_id');
            $table->integer('pcs');
            $table->string('hari');
            $table->string('harga');
            $table->string('subtotal');
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
        Schema::dropIfExists('transaksi_satuan_details');
    }
}
