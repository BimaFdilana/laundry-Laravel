<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganToPemasukansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            if (!Schema::hasColumn('pemasukans', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
}
