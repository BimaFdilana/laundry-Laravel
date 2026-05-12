<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeKuotaToDecimalInKuotaLaundryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE kuota_laundry MODIFY kuota DECIMAL(8,2)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE kuota_laundry MODIFY kuota INTEGER');
    }
}
