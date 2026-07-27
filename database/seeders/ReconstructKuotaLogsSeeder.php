<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KuotaLaundryLog;
use App\Models\Pemasukan;
use App\Models\Transaksi;
use App\Models\User;

class ReconstructKuotaLogsSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan log kuota lama agar tidak duplikat jika seeder dijalankan ulang
        KuotaLaundryLog::truncate();

        // 2. Rekonstruksi Pembelian Kuota (Penambahan) dari tabel Pemasukan
        $pemasukans = Pemasukan::where('kategori', 'like', 'Kuota%')->get();
        foreach ($pemasukans as $pem) {
            // Ekstrak kategori dari string "Kuota (Nama Kategori)"
            preg_match('/Kuota\s*\(([^)]+)\)/i', $pem->kategori, $matches);
            $kategori = $matches[1] ?? 'Paket';

            // Cari user berdasarkan nama (karena pemasukans tidak menyimpan user_id)
            $user = User::where('name', $pem->pemasukan)->first();
            if ($user) {
                KuotaLaundryLog::create([
                    'user_id' => $user->id,
                    'tipe' => 'penambahan',
                    'jumlah' => $pem->jumlah,
                    'kategori' => $kategori,
                    'keterangan' => 'Pembelian kuota awal (rekonstruksi historis). ' . $pem->keterangan,
                    'created_at' => $pem->created_at,
                    'updated_at' => $pem->updated_at,
                ]);
            }
        }

        // 3. Rekonstruksi Pemakaian Kuota (Penggunaan) dari tabel Transaksi
        $transaksis = Transaksi::where('info_pembayaran', 'like', '%Kuota%')->get();
        foreach ($transaksis as $trx) {
            // Cari kategori laundry
            $kategori = $trx->price?->kategori ?? 'Paket';

            // Tambahkan log penggunaan kuota
            KuotaLaundryLog::create([
                'user_id' => $trx->customer_id,
                'tipe' => 'penggunaan',
                'jumlah' => (float)$trx->kg,
                'kategori' => $kategori,
                'invoice' => $trx->invoice,
                'keterangan' => 'Penggunaan kuota untuk transaksi ' . $trx->invoice . ' (rekonstruksi historis).',
                'created_at' => $trx->created_at,
                'updated_at' => $trx->updated_at,
            ]);
        }
    }
}
