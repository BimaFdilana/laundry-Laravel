<?php

namespace Tests\Feature;

use App\Models\Harga;
use App\Models\Karyawan;
use App\Models\KuotaLaundry;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TransaksiInvoiceKuotaTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        foreach (['Admin', 'Customer', 'SuperAdmin'] as $r) {
            Role::findOrCreate($r, 'web');
        }

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.local',
            'auth' => 'Admin',
            'status' => 'Active',
            'password' => bcrypt('secret'),
        ]);
        $admin->assignRole('Admin');

        return $admin;
    }

    private function makeCustomer(): User
    {
        return User::create([
            'name' => 'Cust Test',
            'email' => 'cust@test.local',
            'auth' => 'Customer',
            'status' => 'Active',
            'password' => bcrypt('secret'),
        ]);
    }

    private function makeKaryawan(): Karyawan
    {
        return Karyawan::create([
            'name' => 'Kar Test',
            'email' => 'kar@test.local',
            'alamat' => 'Jl. Test',
            'no_telp' => '0800000000',
            'kelamin' => 'Laki-laki',
        ]);
    }

    private function orderPayload(array $override = []): array
    {
        return array_merge([
            'idempotency_key' => Str::uuid()->toString(),
            'tgl_transaksi' => date('Y-m-d'),
            'kg' => '1.5',
            'hari' => '2',
            'jenis_pembayaran' => 'Tunai',
            'jenis_pewangi' => 'Lavender',
            'catatan_admin' => 'tes',
            'jumlah_lembar_baju' => 5,
        ], $override);
    }

    public function test_paket_mengurangi_kuota_bukan_menambah()
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $karyawan = $this->makeKaryawan();

        $paket = Harga::create([
            'nama' => 'PAKET', 'jenis' => 'SETRIKA', 'kg' => '1',
            'harga' => '0', 'status' => '1', 'hari' => '2',
        ]);

        KuotaLaundry::create([
            'user_id' => $customer->id, 'kategori' => 'SETRIKA', 'kuota' => 11.40,
        ]);

        $this->actingAs($admin)->post('/pelayanan', $this->orderPayload([
            'harga_id' => $paket->id,
            'customer_id' => $customer->id,
            'karyawan_id' => $karyawan->id,
            'kg' => '1.5',
        ]));

        $sisa = KuotaLaundry::where('user_id', $customer->id)
            ->where('kategori', 'SETRIKA')->value('kuota');

        $this->assertEquals(9.90, round((float) $sisa, 2), 'Kuota harus 11.40 - 1.5 = 9.90');
        $this->assertEquals(1, Transaksi::count());
    }

    public function test_dua_order_dapat_invoice_berbeda()
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $karyawan = $this->makeKaryawan();

        $reguler = Harga::create([
            'nama' => 'Reguler', 'jenis' => 'CUCI KOMPLIT', 'kg' => '1',
            'harga' => '8000', 'status' => '1', 'hari' => '2',
        ]);

        $base = [
            'harga_id' => $reguler->id,
            'customer_id' => $customer->id,
            'karyawan_id' => $karyawan->id,
        ];

        $this->actingAs($admin)->post('/pelayanan', $this->orderPayload($base));
        $this->actingAs($admin)->post('/pelayanan', $this->orderPayload($base));

        $invoices = Transaksi::pluck('invoice')->all();

        $this->assertCount(2, $invoices);
        $this->assertCount(2, array_unique($invoices), 'Dua order harus punya invoice berbeda');
    }

    public function test_invoice_bentrok_tetap_tersimpan_dengan_nomor_lain()
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $karyawan = $this->makeKaryawan();

        $reguler = Harga::create([
            'nama' => 'Reguler', 'jenis' => 'CUCI KOMPLIT', 'kg' => '1',
            'harga' => '8000', 'status' => '1', 'hari' => '2',
        ]);

        // Sisipkan transaksi dengan nomor hari ini yang "menabrak" nomor pertama.
        Transaksi::create([
            'invoice' => 'LC-' . date('ymd') . '-001',
            'idempotency_key' => Str::uuid()->toString(),
            'karyawan_id' => $karyawan->id,
            'customer_id' => $customer->id,
            'customer' => $customer->name,
            'email_customer' => $customer->email,
            'tgl_transaksi' => date('Y-m-d'),
            'status_payment' => 'Pending',
            'harga_id' => $reguler->id,
            'kg' => '2',
            'hari' => '2',
            'harga' => '8000',
            'jenis_pembayaran' => 'Tunai',
            'jenis_pewangi' => 'Lavender',
        ]);

        $this->actingAs($admin)->post('/pelayanan', $this->orderPayload([
            'harga_id' => $reguler->id,
            'customer_id' => $customer->id,
            'karyawan_id' => $karyawan->id,
        ]));

        $this->assertEquals(2, Transaksi::count());
        $this->assertCount(2, Transaksi::pluck('invoice')->unique()->all());
    }
}
