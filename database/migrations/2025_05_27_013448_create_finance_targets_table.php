<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_targets', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->bigInteger('target_tahun')->default(0);
            $table->bigInteger('target_bulan')->default(0);
            $table->bigInteger('target_hari')->default(0);
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
        Schema::dropIfExists('finance_targets');
    }
}
