<?php

namespace Tests\Feature;

use App\Models\Harga;
use App\Models\Karyawan;
use App\Models\KuotaLaundry;
use App\Models\KuotaLaundryLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KuotaHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $auth, string $email): User
    {
        Role::findOrCreate($auth, 'web');

        $user = User::create([
            'name' => $auth . ' Test',
            'email' => $email,
            'auth' => $auth,
            'status' => 'Active',
            'password' => bcrypt('secret'),
        ]);
        $user->assignRole($auth);

        return $user;
    }

    private function makeKaryawan(): Karyawan
    {
        return Karyawan::create([
            'name' => 'Karyawan Test',
            'email' => 'karyawan@test.local',
            'alamat' => 'Jl. Test',
            'no_telp' => '0800000000',
            'kelamin' => 'Laki-laki',
        ]);
    }

    public function test_transaksi_paket_membuat_log_kuota_awal_dan_akhir()
    {
        $admin = $this->makeUser('Admin', 'admin@test.local');
        $customer = $this->makeUser('Customer', 'customer@test.local');
        $karyawan = $this->makeKaryawan();

        $paket = Harga::create([
            'nama' => 'PAKET',
            'jenis' => 'SETRIKA',
            'kg' => '1',
            'harga' => '0',
            'status' => '1',
            'hari' => '2',
        ]);

        KuotaLaundry::create([
            'user_id' => $customer->id,
            'kategori' => 'SETRIKA',
            'kuota' => 11.40,
        ]);

        $this->actingAs($admin)->post('/pelayanan', [
            'idempotency_key' => Str::uuid()->toString(),
            'tgl_transaksi' => date('Y-m-d'),
            'kg' => '1.5',
            'hari' => '2',
            'harga_id' => $paket->id,
            'jenis_pembayaran' => 'Tunai',
            'customer_id' => $customer->id,
            'karyawan_id' => $karyawan->id,
            'jenis_pewangi' => 'Lavender',
            'catatan_admin' => 'test',
            'jumlah_lembar_baju' => 5,
        ]);

        $log = KuotaLaundryLog::where('user_id', $customer->id)
            ->where('tipe', 'pemakaian')
            ->firstOrFail();

        $this->assertEquals(11.40, round($log->kuota_sebelum, 2));
        $this->assertEquals(-1.50, round($log->perubahan, 2));
        $this->assertEquals(9.90, round($log->kuota_sesudah, 2));
        $this->assertNotNull($log->transaksi_id);
    }

    public function test_admin_bisa_melihat_riwayat_kuota()
    {
        $admin = $this->makeUser('Admin', 'admin@test.local');

        $response = $this->actingAs($admin)->get('/kuota/history');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Kuota');
    }

    public function test_superadmin_bisa_melihat_riwayat_kuota()
    {
        $superAdmin = $this->makeUser('SuperAdmin', 'superadmin@test.local');

        $response = $this->actingAs($superAdmin)->get('/superadmin/kuota/history');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Kuota');
    }
}
