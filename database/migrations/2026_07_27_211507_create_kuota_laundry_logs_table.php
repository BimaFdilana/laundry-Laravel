<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuotaLaundryLogsTable extends Migration
{
    public function up()
    {
        Schema::create('kuota_laundry_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('transaksi_id')->nullable();
            $table->unsignedBigInteger('purchase_request_id')->nullable();
            $table->string('kategori');
            $table->enum('tipe', ['pemakaian', 'pembelian', 'penambahan_admin', 'koreksi_admin', 'kuota_awal']);
            $table->decimal('kuota_sebelum', 8, 2)->nullable();
            $table->decimal('perubahan', 8, 2)->default(0);
            $table->decimal('kuota_sesudah', 8, 2)->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'kategori']);
            $table->index(['transaksi_id']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuota_laundry_logs');
    }
}
