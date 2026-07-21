<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdempotencyAndUniqueConstraintToTransaksis extends Migration
{
    public function up()
    {
        // transaksis: tambah idempotency_key + unique constraint invoice
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('idempotency_key', 36)->nullable()->unique()->after('invoice');
            $table->unique('invoice', 'transaksis_invoice_unique');
        });

        // transaksi_satuans: tambah idempotency_key (unique invoice sudah ada)
        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->string('idempotency_key', 36)->nullable()->unique()->after('invoice');
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropUnique('transaksis_invoice_unique');
            $table->dropColumn('idempotency_key');
        });

        Schema::table('transaksi_satuans', function (Blueprint $table) {
            $table->dropColumn('idempotency_key');
        });
    }
}
