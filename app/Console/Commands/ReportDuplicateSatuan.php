<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportDuplicateSatuan extends Command
{
    protected $signature = 'report:duplicate-satuan';
    protected $description = 'List grup duplikat transaksi_satuan untuk review manual (customer+tgl sama, invoice beda)';

    public function handle()
    {
        $groups = DB::table('transaksi_satuans')
            ->select(
                'customer',
                'tgl_transaksi',
                DB::raw('DATE(created_at) as tanggal_input'),
                DB::raw('COUNT(*) as jml'),
                DB::raw('GROUP_CONCAT(id) as ids'),
                DB::raw('GROUP_CONCAT(invoice) as invoices'),
                DB::raw('MIN(created_at) as waktu_pertama'),
                DB::raw('MAX(created_at) as waktu_terakhir')
            )
            ->groupBy('customer', 'tgl_transaksi', DB::raw('DATE(created_at)'))
            ->havingRaw('COUNT(*) > 1')
            ->orderByRaw('COUNT(*) DESC')
            ->get();

        if ($groups->isEmpty()) {
            $this->info('Tidak ada duplikat transaksi satuan.');
            return 0;
        }

        $this->warn("Ditemukan {$groups->count()} grup duplikat transaksi satuan:");
        $this->line('');

        foreach ($groups as $i => $g) {
            $durasi = strtotime($g->waktu_terakhir) - strtotime($g->waktu_pertama);
            $menit = round($durasi / 60);

            $this->line(($i + 1) . ". {$g->customer}");
            $this->line("   Tgl transaksi: {$g->tgl_transaksi} | Input: {$g->tanggal_input}");
            $this->line("   Jumlah: {$g->jml}x | Durasi: {$menit} menit");
            $this->line("   ID: {$g->ids}");
            $this->line("   Invoice: {$g->invoices}");
            $this->line('');
        }

        $totalHapus = $groups->sum(function ($g) {
            return $g->jml - 1;
        });

        $this->warn("Total baris yang bisa dihapus (keep 1 per grup): {$totalHapus}");
        $this->info("Review manual setiap grup. Hapus dengan:");
        $this->line("  php artisan report:duplicate-satuan --delete");
        $this->line("");
        $this->info("Atau hapus manual per ID:");

        return 0;
    }
}
