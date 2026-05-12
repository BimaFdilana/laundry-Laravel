<?php

/*
 * This file is part of the IndoBank package.
 *
 * (c) Andri Desmana <andridesmana.pw | andridesmana29@gmail.com>
 *
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Andes2912\IndoBank\RawDataGetter;
use Illuminate\Support\Facades\DB;

class IndoBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @deprecated
     *
     * @return void
     */
    public function run()
    {
        // Get Data
        $banks = RawDataGetter::getBanks();

        $validBanks = array_filter($banks, function($bank) {
            return isset($bank['nama_bank']) && trim($bank['nama_bank']) !== '';
        });

        // Insert Data to Database
        DB::table('banks')->insert(array_values($validBanks));
    }
}