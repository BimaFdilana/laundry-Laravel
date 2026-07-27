<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Harga, Karyawan, Transaksi, User, Diskon};
use Illuminate\Support\Facades\Session;
use App\Notifications\{StatusUpdateNotification};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PelayananController extends Controller

{
    public function index(Request $request)
    {
        $query = Transaksi::with(['price', 'karyawan'])->orderBy('id', 'DESC');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                  ->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'LIKE', "%{$search}%")
                  ->orWhere('customer', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data transaksi dengan pagination 10
        $order = $query->paginate(10)->appends($request->query());

        // Ambil semua karyawan
        $karyawans = Karyawan::all();

        return view('modul_admin.transaksi.order', compact('order', 'karyawans'));
    }

    public function addorders()
    {
        $currentUser = Auth::user();

        if ($currentUser->auth !== 'Admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $harga = Harga::where('status', 1)->get();

        // === INVOICE UNIK PER HARI + VALIDASI DB ===
        $today = date('Y-m-d');

        $lastInvoice = Transaksi::whereDate('created_at', $today)
            ->orderBy('id', 'DESC')
            ->first();

        $nextNumber = 1;

        if ($lastInvoice && isset($lastInvoice->invoice)) {
            $lastNumber = (int) substr($lastInvoice->invoice, -3);
            $nextNumber = $lastNumber + 1;
        }

        // Loop anti-dobel invoice
        do {
            $newID = 'LC-' . date('ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $exists = Transaksi::where('invoice', $newID)->exists();
            if ($exists) $nextNumber++;
        } while ($exists);
        // =============================================

        $tgl = date('d-m-Y');

        $cek_harga = Harga::where('status', 1)->first();
        $harga_value = $cek_harga ? $cek_harga->harga : 0;

        $customers = User::with('kuotaLaundry')->where('auth', 'Customer')->orderBy('name', 'asc')->get();
        $karyawans = Karyawan::orderBy('name', 'asc')->get();

        $diskons = Diskon::where('status', 1)->get();

        return view('modul_admin.transaksi.addorder', compact(
            'currentUser',
            'newID',
            'cek_harga',
            'harga',
            'harga_value',
            'customers',
            'karyawans',
            'diskons'
        ));
    }

    // Proses simpan order
    public function store(Request $request)
    {
        $request->validate([
            'invoice'           => 'required|unique:transaksis,invoice',
            'tgl_transaksi'     => 'required',
            'kg'                => 'required|regex:/^[0-9.]+$/',
            'hari'              => 'required',
            'harga_id'          => 'required|exists:hargas,id',
            'jenis_pembayaran'  => 'required',
            'customer_id'       => 'required|exists:users,id',
            'karyawan_id'       => 'required|exists:karyawans,id',
            'catatan_admin'     => 'nullable|string|max:255',
            'jenis_pewangi'     => 'required|string',
            'jumlah_lembar_baju' => 'nullable|integer|min:0',
            'status_bayar'      => 'nullable|in:lunas,belum_bayar',
        ]);

        // Ambil customer berdasarkan customer_id yang dipilih admin
        $customer = User::findOrFail($request->customer_id);

        $order = new Transaksi();
        $order->invoice          = $request->invoice;
        $order->tgl_transaksi = Carbon::parse($request->tgl_transaksi);
        $order->status_payment   = 'Pending';
        $order->harga_id         = $request->harga_id;
        $order->customer_id      = $customer->id;
        $order->customer         = $customer->name;
        $order->email_customer   = $customer->email;
        $order->hari             = $request->hari;
        $order->kg               = $request->kg;
        $order->jumlah_lembar_baju = $request->jumlah_lembar_baju;
        $order->karyawan_id      = $request->karyawan_id;
        $order->catatan_admin    = $request->catatan_admin;
        $order->jenis_pewangi    = $request->jenis_pewangi;

        $hargaObj = Harga::findOrFail($request->harga_id);

        $berat = $order->kg;
        $kategori = $hargaObj->jenis;

        // Ambil kuota laundry dari customer terpilih
        $kuota = $customer->kuotaLaundry()->where('kategori', $kategori)->first();

        // Cek apakah transaksi diizinkan
        if ($hargaObj->harga == 0 && (!$kuota || $kuota->kuota <= 0)) {
            return redirect()->back()->with('error', 'Transaksi tidak bisa dilakukan karena harga Rp 0 dan tidak ada kuota tersedia.');
        }

        $status_payment = 'Pending';
        $sisa_berbayar = $berat;
        $ditanggung_kuota = 0;

        // Cek apakah kuota bisa digunakan (hanya jika layanan bernama "PAKET")
        $bolehPakaiKuota = strtolower($hargaObj->nama) === 'paket';

        if ($bolehPakaiKuota && $kuota && $kuota->kuota > 0) {
            if ($kuota->kuota >= $berat) {
                $kuota->kuota -= $berat;
                $kuota->save();
                $sisa_berbayar = 0;
                $ditanggung_kuota = $berat;
                $status_payment = 'Success';
            } else {
                $sisa_berbayar = $berat - $kuota->kuota;
                $ditanggung_kuota = $kuota->kuota;
                $kuota->kuota = 0;
                $kuota->save();
            }

            // Catat log penggunaan kuota
            \App\Models\KuotaLaundryLog::create([
                'user_id' => $customer->id,
                'tipe' => 'penggunaan',
                'jumlah' => $ditanggung_kuota,
                'kategori' => $kategori,
                'invoice' => $order->invoice,
                'keterangan' => 'Penggunaan kuota untuk transaksi ' . $order->invoice . '.',
            ]);
        }

        $total_harga = $berat * $hargaObj->harga;

        if ($sisa_berbayar == 0) {
            $order->harga_akhir = 0;
        } else {
            $order->harga_akhir = $total_harga;
            if ($request->disc != NULL) {
                $disc = $request->disc; // Diskon dalam bentuk nominal langsung
                $order->disc = $disc;   // Simpan nilai diskon langsung (bukan persen)
                $order->harga_akhir = $total_harga - $disc;

                // Pastikan harga akhir tidak negatif
                if ($order->harga_akhir < 0) {
                    $order->harga_akhir = 0;
                }
            }
        }

        $order->harga = $hargaObj->harga;
        $order->jenis_pembayaran = $request->jenis_pembayaran;
        $order->tgl = Carbon::now()->day;
        $order->bulan = Carbon::now()->month;
        $order->tahun = Carbon::now()->year;

        // Override status jika admin pilih Lunas
        if ($request->status_bayar === 'lunas') {
            $status_payment = 'Success';
        }
        $order->status_payment = $status_payment;

        $sisa_bayar = $sisa_berbayar * $hargaObj->harga;

        if ($sisa_berbayar == 0) {
            $order->info_pembayaran = 'Sudah Dibayar oleh Kuota';
        } elseif ($sisa_berbayar < $berat) {
            $order->info_pembayaran = 'Sisa yang harus dibayar: Rp ' . number_format($sisa_bayar, 0, ',', '.');
        } else {
            $order->info_pembayaran = 'Total Harga: Rp ' . number_format($order->harga_akhir, 0, ',', '.');
        }

        // Simpan order seperti biasa
        $order->save();

        // Kirim notifikasi ke customer saat order dibuat
        if ($customer) {
            $dataInvoice = $order; // alias agar mudah

            $berat = $dataInvoice->kg ?? 0;
            $hargaObj = \App\Models\Harga::find($dataInvoice->harga_id);
            $total_harga = $hargaObj ? ($berat * $hargaObj->harga) : 0;

            $estimasi = '-';

            if (is_numeric($dataInvoice->hari)) {
                // Estimasi dalam hari (misal: 2 hari)
                $estimasi = Carbon::parse($dataInvoice->tgl_transaksi)
                    ->addDays($dataInvoice->hari)
                    ->translatedFormat('d F Y');
            } elseif (preg_match('/^(\d+)\s*jam$/i', $dataInvoice->hari, $match)) {
                // Estimasi dalam jam (misal: "4 jam")
                $estimasi = Carbon::parse($dataInvoice->tgl_transaksi)
                    ->addHours($match[1])
                    ->translatedFormat('d F Y H:i');
            } else {
                // Estimasi teks lainnya
                $estimasi = $dataInvoice->hari;
            }

            $message = "✨ *LAUNDRY CAMP* ✨\n"
                . "📍 _Jl. Bantan, Gg. Cahaya, Senggoro_\n"
                . "📞 _Hubungi: 082284392025_\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "🎉 *ORDER BERHASIL DIBUAT!* 🎉\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n\n"
                . "📌 *INFORMASI INVOICE*\n"
                . "• *No. Invoice* : `{$dataInvoice->invoice}`\n"
                . "• *Tanggal* : " . Carbon::parse($dataInvoice->tgl_transaksi)->format('d/m/Y') . "\n"
                . "• *Nama Pelanggan* : " . ($dataInvoice->customers?->name ?? '-') . "\n"
                . "• *Status Pembayaran* : *{$dataInvoice->status_payment}*\n\n"
                . "🧺 *DETAIL LAYANAN*\n"
                . "• *Layanan* : " . ($dataInvoice->price?->nama ?? '-') . " - " . ($dataInvoice->price?->jenis ?? '-') . "\n"
                . "• *Petugas* : " . ($dataInvoice->karyawan?->name ?? '-') . "\n"
                . "• *Pewangi* : {$dataInvoice->jenis_pewangi}\n\n"
                . "📊 *RINCIAN PAKAIAN*\n"
                . "• *Berat* : {$berat} kg\n"
                . "• *Jumlah* : " . ($dataInvoice->jumlah_lembar_baju ?? '-') . " pcs\n"
                . "• *Catatan* : " . ($dataInvoice->catatan_admin ?? '-') . "\n\n"
                . "💰 *RINCIAN BIAYA*\n"
                . "• *Subtotal* : Rp " . number_format($total_harga, 0, ',', '.') . "\n"
                . "• *Diskon* : - Rp " . number_format($dataInvoice->disc ?? 0, 0, ',', '.') . "\n"
                . "• *Harga Akhir* : *Rp " . number_format($dataInvoice->harga_akhir ?? 0, 0, ',', '.') . "*\n"
                . "• *Pembayaran* : " . ($dataInvoice->jenis_pembayaran ?? '-') . " (" . ($dataInvoice->info_pembayaran ?? '-') . ")\n\n"
                . "⏱️ *ESTIMASI SELESAI*\n"
                . "📅 *{$estimasi}*\n\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "⚠️ *INFORMASI PENTING (BACA)* ⚠️\n"
                . "Harap konfirmasi ke Admin segera jika:\n"
                . "1. Jumlah pakaian berbeda dengan hitungan.\n"
                . "2. Ada pakaian luntur yang wajib dipisah.\n"
                . "3. Ada pakaian bernoda berat atau rusak.\n"
                . "4. Ada uang/benda berharga tertinggal.\n"
                . "━━━━━━━━━━━━━━━━━━━━━━\n"
                . "_Terima kasih! Order Anda sedang kami proses._";

            $url = route('customer.invoice', $dataInvoice->invoice);

            $customer->notify(new StatusUpdateNotification($message, $url));
        }

        // Flash message
        if ($sisa_berbayar == 0) {
            Session::flash('success', 'Order berhasil ditambah! Seluruh transaksi ditanggung kuota.');
        } elseif ($sisa_berbayar < $berat) {
            Session::flash('info', "Order berhasil ditambah. {$ditanggung_kuota}kg ditanggung kuota, {$sisa_berbayar}kg sisanya dibayar sebesar Rp " . number_format($sisa_bayar, 0, ',', '.'));
        } else {
            Session::flash('success', 'Order berhasil ditambah! Seluruh biaya ditanggung customer.');
        }

        return redirect()->route('transaksi.print', $order->id);
    }

    public function listharga(Request $request)
    {
        $list_harga = Harga::select('id', 'harga')
            ->where('id', $request->id)
            ->get(); // Menghapus filter user_id
        $select = '';
        $select .= '
                <div class="form-group has-success">
                <label for="id" class="control-label">Harga per kg</label>
                <select id="harga" class="form-control" name="harga" value="harga">
                ';
        foreach ($list_harga as $studi) {
            $select .= '<option value="' . $studi->harga . '">' . $studi->harga . '</option>';
        }
        $select .= '
                </select>
                </div>
                </div>';
        return $select;
    }

    // Proses Ubah Status Order
    public function ubahstatusorder(Request $request)
    {
        $statusorder = Transaksi::find($request->id);

        $statusorder->update([
            'status_order' => $request->status_order,
        ]);

        if ($request->status_order === 'Delivery') {
            $statusorder->update([
                'tgl_ambil' => Carbon::now(),
            ]);

            $customer = User::where('email', $statusorder->email_customer)->first();
            if ($customer) {
                $dataInvoice = $statusorder;

                $berat = $dataInvoice->kg ?? 0;
                $hargaObj = Harga::find($dataInvoice->harga_id);
                $total_harga = $hargaObj ? ($berat * $hargaObj->harga) : 0;

                $message = "🧾 *LAUNDRY CAMP*\n"
                    . "Jl. Bantan, Gg. Cahaya, Senggoro\n"
                    . "Telp/WA: 082284392025\n"
                    . "==============================\n"
                    . "*LAUNDRY ANDA TELAH SELESAI!*\n"
                    . "*Status Pembayaran:* {$dataInvoice->status_payment}\n"
                    . "==============================\n"
                    . "*LAYANAN:* " . ($dataInvoice->price?->nama ?? '-') . " - " . ($dataInvoice->price?->jenis ?? '-') . "\n"
                    . "*Karyawan:* " . ($dataInvoice->karyawan?->name ?? '-') . "\n"
                    . "==============================\n"
                    . "*Invoice:* {$dataInvoice->invoice}\n"
                    . "*Tanggal:* " . Carbon::parse($dataInvoice->tgl_transaksi)->format('d/m/Y') . "\n"
                    . "*Customer:* " . ($dataInvoice->customers?->name ?? '-') . "\n"
                    . "*Berat:* {$berat} kg\n"
                    . "*Lembar Pakaian:* " . ($dataInvoice->jumlah_lembar_baju ?? '-') . " pcs\n"
                    . "*Pewangi:* {$dataInvoice->jenis_pewangi}\n"
                    . "*Total:* Rp " . number_format($total_harga, 0, ',', '.') . "\n"
                    . "*Diskon:* Rp " . number_format($dataInvoice->disc ?? 0, 0, ',', '.') . "\n"
                    . "*Harga Akhir:* Rp " . number_format($dataInvoice->harga_akhir ?? 0, 0, ',', '.') . "\n"
                    . "*Pembayaran:* " . ($dataInvoice->jenis_pembayaran ?? '-') . " (" . ($dataInvoice->info_pembayaran ?? '-') . ")\n"
                    . "*Catatan:* " . ($dataInvoice->catatan_admin ?? '-') . "\n"
                    . "==============================\n"
                    . "*SERVE WITH LOVE ❤️*\n"
                    . "1. Terimakasih telah berlangganan di Laundry Camp, kami telah berusaha memberikan pelayanan terbaik kepada seluruh pelanggan. Jika ada yang kurang memuaskan mohon hubungi kami untuk evaluasi dan peningkatan pelayanan Kami kedepan.\n"
                    . "2. Kehilangan/kerusakan pakaian yang tidak diambil lebih dari 2 (dua) minggu tidak menjadi tanggung jawab Laundry Camp.\n"
                    . "==============================\n"
                    . "Terima kasih!\n"
                    . "Laundry telah selesai ~ " . ($dataInvoice->ket_delivery ?? '-') . "\n"
                    . "Tanggal diambil/diantar: " . Carbon::parse($dataInvoice->tgl_ambil)->format('d/m/Y H:i');

                $url = route('customer.invoice', $dataInvoice->invoice);

                $customer->notify(new StatusUpdateNotification($message, $url));
            }
        }

        Session::flash('success', 'Status Laundry Berhasil Diubah!');
        return redirect()->route('pelayanan.index');
    }

    // Proses Ubah Status Pembayaran
    public function ubahstatusbayar(Request $request)
    {
        $statusbayar = Transaksi::find($request->id);
        $statusbayar->update([
            'status_payment' => $request->status_payment,
        ]);

        Session::flash('success', 'Status Pembayaran Berhasil Diubah !');
        return redirect()->route('pelayanan.index');
    }

    public function updateKetDelivery(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:transaksis,id',
            'ket_delivery' => 'nullable|string',
        ]);

        $transaksi = Transaksi::find($request->id);
        $transaksi->ket_delivery = $request->ket_delivery;
        $transaksi->save();

        Session::flash('success', 'Keterangan Delivery Berhasil Diubah dan Notifikasi Dikirim!');
        return redirect()->route('pelayanan.index');
    }
}
