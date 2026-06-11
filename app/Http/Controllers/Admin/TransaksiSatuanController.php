<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{TransaksiSatuan, TransaksiSatuanDetail, Satuan, Karyawan, User};
use App\Notifications\StatusUpdateNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TransaksiSatuanController extends Controller
{
    public function index()
    {
        // Ambil data transaksi satuan
        $ordersatuan = TransaksiSatuan::with(['karyawan'])->orderBy('id', 'DESC')->get();

        // Ambil semua karyawan
        $karyawans = Karyawan::all();

        return view('modul_admin.transaksi.ordersatuan', compact('ordersatuan', 'karyawans'));
    }
    public function create()
    {
        $satuans = Satuan::all();
        $karyawans = Karyawan::orderBy('name', 'asc')->get();
        $customers = User::where('auth', 'Customer')->orderBy('name', 'asc')->get();
        $currentUser = Auth::user();

        // === INVOICE UNIK PER HARI + VALIDASI DB ===
        $today = date('Y-m-d');

        $lastInvoice = TransaksiSatuan::whereDate('created_at', $today)
            ->orderBy('id', 'DESC')
            ->first();

        $nextNumber = 1;

        if ($lastInvoice && isset($lastInvoice->invoice)) {
            $lastNumber = (int) substr($lastInvoice->invoice, -3);
            $nextNumber = $lastNumber + 1;
        }

        // Loop sampai benar-benar tidak ada invoice duplikat
        do {
            $invoice = 'TS-' . date('ymd') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $exists = TransaksiSatuan::where('invoice', $invoice)->exists();
            if ($exists) $nextNumber++;
        } while ($exists);
        // =============================================

        return view('modul_admin.transaksi_satuan.create', compact(
            'satuans',
            'karyawans',
            'customers',
            'invoice'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_transaksi'       => 'required',
            'invoice'             => 'required|unique:transaksi_satuans,invoice',
            'customer_id'         => 'required|exists:users,id',
            'karyawan_id'         => 'required|exists:karyawans,id',
            'jenis_pembayaran'    => 'required|in:Tunai,Transfer',
            'catatan_admin'       => 'required|string',
            'jenis_pewangi'       => 'required|string',
            'details'             => 'required|array|min:1',
            'details.*.satuan_id' => 'required|exists:satuans,id',
            'details.*.pcs'       => 'required',
            'status_bayar'        => 'nullable|in:lunas,belum_bayar',
        ]);

        $customer = User::findOrFail($request->customer_id);

        $tgl = Carbon::parse($request->tgl_transaksi);
        $transaksi = TransaksiSatuan::create([
            'invoice'           => $request->invoice,
            'karyawan_id'       => $request->karyawan_id,
            'customer_id'       => $customer->id,
            'customer'          => $customer->name,
            'email_customer'    => $customer->email,
            'tgl_transaksi'     => $tgl,
            'status_order'      => 'Antrian',
            'status_payment'    => $request->status_bayar === 'lunas' ? 'Success' : 'Pending',
            'jenis_pembayaran'  => $request->jenis_pembayaran,
            'tgl'               => Carbon::now()->day,
            'bulan'             => Carbon::now()->month,
            'tahun'             => Carbon::now()->year,
            'catatan_admin'     => $request->catatan_admin,
            'jenis_pewangi'     => $request->jenis_pewangi,
        ]);


        $total = 0;
        foreach ($request->details as $item) {
            $satuan = Satuan::findOrFail($item['satuan_id']);
            $subtotal = $satuan->harga * $item['pcs'];
            $total += $subtotal;

            TransaksiSatuanDetail::create([
                'transaksi_satuan_id' => $transaksi->id,
                'satuan_id'           => $item['satuan_id'],
                'pcs'                 => $item['pcs'],
                'hari'                => $satuan->hari,
                'harga'               => $satuan->harga,
                'subtotal'            => $subtotal,
            ]);
        }

        // Hitung diskon jika ada
        $harga_akhir = $total;

        if ($request->filled('disc') && $request->disc > 0) {
            $harga_akhir = $total - $request->disc;

            // Pastikan harga tidak negatif
            if ($harga_akhir < 0) {
                $harga_akhir = 0;
            }
        }

        $transaksi->update([
            'harga_akhir'     => $harga_akhir,
            'disc'            => $request->disc,
            'info_pembayaran' => 'Total Harga: Rp' . number_format($harga_akhir, 0, ',', '.'),
        ]);

        if ($customer) {
            $dataInvoice = $transaksi; // alias agar lebih ringkas
            $total = $dataInvoice->details->sum('subtotal');

            $message = "🧾 *LAUNDRY CAMP*\n"
                . "Jl. Bantan, Gg. Cahaya, Senggoro\n"
                . "Telp/WA: 082284392025\n"
                . "==============================\n"
                . "*ORDER LAYANAN SATUAN DIBUAT!*\n"
                . "*Status Pembayaran:* {$dataInvoice->status_payment}\n"
                . "==============================\n"
                . "*Karyawan:* " . ($dataInvoice->karyawan?->name ?? '-') . "\n"
                . "*Invoice:* {$dataInvoice->invoice}\n"
                . "*Tanggal:* " . \Carbon\Carbon::parse($dataInvoice->tgl_transaksi)->format('d/m/Y') . "\n"
                . "*Customer:* {$dataInvoice->customer}\n"
                . "*Pewangi:* {$dataInvoice->jenis_pewangi}\n"
                . "*Pembayaran:* {$dataInvoice->jenis_pembayaran} ({$dataInvoice->info_pembayaran})\n"
                . "==============================\n"
                . "*🧺 Detail Barang:*\n";

            foreach ($dataInvoice->details as $detail) {
                $estimasi = '-';

                if (is_numeric($detail->hari)) {
                    // Estimasi dalam hari (misal: 2 hari)
                    $estimasi = Carbon::parse($dataInvoice->tgl_transaksi)
                        ->addDays($detail->hari)
                        ->translatedFormat('d F Y');
                } elseif (preg_match('/^(\d+)\s*jam$/i', $detail->hari, $match)) {
                    // Estimasi dalam jam (misal: "4 jam")
                    $estimasi = Carbon::parse($dataInvoice->tgl_transaksi)
                        ->addHours($match[1])
                        ->translatedFormat('d F Y H:i');
                } else {
                    // Estimasi teks lainnya
                    $estimasi = $detail->hari;
                }

                $message .= "------------------------------\n"
                    . "{$detail->satuan->nama} ({$detail->pcs} pcs)\n"
                    . "Harga: Rp " . number_format($detail->harga, 0, ',', '.') . "\n"
                    . "Subtotal: Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n"
                    . "Estimasi selesai: $estimasi\n";
            }

            $message .= "==============================\n"
                . "*Total:* Rp " . number_format($total, 0, ',', '.') . "\n"
                . "*Diskon:* Rp " . number_format($dataInvoice->disc ?? 0, 0, ',', '.') . "\n"
                . "*Harga Akhir:* Rp " . number_format($dataInvoice->harga_akhir ?? 0, 0, ',', '.') . "\n"
                . "==============================\n"
                . "Segera Hubungi dan Konfirmasi ke admin jika:\n"
                . "1. Ada perbedaan jumlah pakaian hasil hitungan petugas laundry kami\n"
                . "2. Ada pakaian luntur yang harus dipisahkan\n"
                . "3. Ada kondisi pakaian terdapat noda dan rusak\n"
                . "4. Terdapat benda berharga/uang yang tertinggal didalam pakaian\n"
                . "==============================\n"
                . "Terima kasih! Order Anda sedang kami proses.";

            $url = route('customer.invoicesatuan', $dataInvoice->invoice); // pastikan rute ini sesuai

            $customer->notify(new \App\Notifications\StatusUpdateNotification($message, $url));
        }

        Session::flash('success', 'Transaksi Satuan berhasil ditambahkan.');
        return redirect()->route('transaksi-satuan.print', $transaksi->id);
    }

    public function ubahStatusOrder(Request $request)
    {
        $statusorder = TransaksiSatuan::find($request->id);

        if (!$statusorder) {
            return response()->json(['error' => 'Transaksi satuan tidak ditemukan.'], 404);
        }

        $statusorder->update([
            'status_order' => $request->status_order,
        ]);

        if ($request->status_order === 'Delivery') {
            $statusorder->update(['tgl_ambil' => Carbon::now()]);

            $customer = User::where('email', $statusorder->email_customer)->first();
            if ($customer) {
                $dataInvoice = $statusorder;
                $total = $dataInvoice->details->sum('subtotal');

                $message = "🧾 *LAUNDRY CAMP*\n"
                    . "Jl. Bantan, Gg. Cahaya, Senggoro\n"
                    . "Telp/WA: 082284392025\n"
                    . "==============================\n"
                    . "*LAUNDRY ANDA TELAH SELESAI!*\n"
                    . "*Status Pembayaran:* {$dataInvoice->status_payment}\n"
                    . "==============================\n"
                    . "*Karyawan:* " . ($dataInvoice->karyawan?->name ?? '-') . "\n"
                    . "*Invoice:* {$dataInvoice->invoice}\n"
                    . "*Tanggal:* " . \Carbon\Carbon::parse($dataInvoice->tgl_transaksi)->format('d/m/Y') . "\n"
                    . "*Customer:* {$dataInvoice->customer}\n"
                    . "*Pewangi:* {$dataInvoice->jenis_pewangi}\n"
                    . "*Pembayaran:* {$dataInvoice->jenis_pembayaran} ({$dataInvoice->info_pembayaran})\n"
                    . "==============================\n"
                    . "*🧺 Detail Barang:*\n";

                foreach ($dataInvoice->details as $detail) {
                    $message .= "------------------------------\n"
                        . "{$detail->satuan->nama} ({$detail->pcs} pcs)\n"
                        . "Harga: Rp " . number_format($detail->harga, 0, ',', '.') . "\n"
                        . "Subtotal: Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n";
                }

                $message .= "==============================\n"
                    . "*Total:* Rp " . number_format($total, 0, ',', '.') . "\n"
                    . "*Diskon:* Rp " . number_format($dataInvoice->disc ?? 0, 0, ',', '.') . "\n"
                    . "*Harga Akhir:* Rp " . number_format($dataInvoice->harga_akhir ?? 0, 0, ',', '.') . "\n"
                    . "==============================\n"
                    . "*SERVE WITH LOVE ❤️*\n"
                    . "1. Terimakasih telah berlangganan di Laundry Camp, kami telah berusaha memberikan pelayanan terbaik kepada seluruh pelanggan. Jika ada yang kurang memuaskan mohon hubungi kami untuk evaluasi dan peningkatan pelayanan Kami kedepan.\n"
                    . "2. Kehilangan/kerusakan pakaian yang tidak diambil lebih dari 2 (dua) minggu tidak menjadi tanggung jawab Laundry Camp.\n"
                    . "==============================\n"
                    . "Terima kasih!\n"
                    . "Laundry telah selesai ~ " . ($dataInvoice->ket_delivery ?? '-') . "\n"
                    . "Tanggal diambil/diantar: " . \Carbon\Carbon::parse($dataInvoice->tgl_ambil)->format('d/m/Y H:i');

                $url = route('customer.invoicesatuan', $dataInvoice->invoice);

                $customer->notify(new StatusUpdateNotification($message, $url));
            }
        }

        return response()->json(['success' => true]);
    }

    public function ubahStatusBayar(Request $request)
    {
        $transaksi = TransaksiSatuan::find($request->id);

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi satuan tidak ditemukan.'], 404);
        }

        $transaksi->update([
            'status_payment' => $request->status_payment,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateKetDelivery(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:transaksi_satuans,id',
            'ket_delivery' => 'nullable|string',
        ]);

        $transaksi = TransaksiSatuan::find($request->id);
        $transaksi->ket_delivery = $request->ket_delivery;
        $transaksi->save();

        return response()->json(['success' => true]);
    }

    public function invoice(Request $request)
    {
        $dataInvoice = TransaksiSatuan::with('details', 'customers')
            ->where('invoice', $request->invoice)
            ->first();

        // Debug untuk memeriksa data
        if (!$dataInvoice) {
            return back()->with('error', 'Data invoice tidak ditemukan.');
        }

        return view('modul_admin.transaksi_satuan.invoice', compact('dataInvoice'));
    }

    public function print($id)
    {
        $transaksi = TransaksiSatuan::with('details.satuan')->findOrFail($id);

        $total = $transaksi->details->sum('subtotal');

        return view('modul_admin.transaksi_satuan.print', compact('transaksi', 'total'));
    }

    public function printInvoice($id)
    {
        $dataInvoice = TransaksiSatuan::with('details.satuan')->findOrFail($id);

        $total = $dataInvoice->details->sum('subtotal');

        return view('modul_admin.transaksi_satuan.print_invoice', compact('dataInvoice', 'total'));
    }
}
