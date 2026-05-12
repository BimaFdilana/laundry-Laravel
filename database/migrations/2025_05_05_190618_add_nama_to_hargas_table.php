<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaToHargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hargas', function (Blueprint $table) {
            $table->string('nama')->after('id'); // ubah 'id' jika ingin posisinya setelah kolom lain
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hargas', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
}
