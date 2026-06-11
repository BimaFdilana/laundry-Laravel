# LaundryCamp - Sistem Manajemen Laundry

Aplikasi manajemen laundry berbasis Laravel untuk mengelola transaksi, pelanggan, karyawan, dan keuangan.

## Fitur Utama

### Manajemen Transaksi
- Transaksi reguler (per kg)
- Transaksi satuan (per item)
- Status order: Antrian, Process, Done, Delivery
- Status pembayaran: Lunas / Belum Bayar
- Metode pembayaran: Cash (Tunai) / Transfer

### Sistem Piutang
- Halaman piutang untuk SuperAdmin dan Admin
- Filter berdasarkan customer
- Aksi "Bayar Full" untuk pelunasan
- Card total piutang di dashboard

### Data Keuangan
- Pemasukan dengan pemisahan tabel Cash & Transfer
- Pengeluaran
- Total dengan filter tanggal

### Laporan & Perbandingan
- Laporan harian, bulanan, tahunan
- Perbandingan data paket & transaksi (vs bulan/tahun lalu)
- Grafik dengan ApexCharts

### Manajemen Karyawan
- Data karyawan
- Aktivitas karyawan (cuci, gosok, packing)
- Bintang karyawan
- Reward karyawan (bonus, insentif, lembur, prestasi)

### Fitur Lainnya
- Paket laundry (kuota)
- Inventaris
- Gift untuk customer
- Notifikasi WhatsApp

## Teknologi

- PHP 7.4+
- Laravel 8.x
- MySQL
- Bootstrap 4 (Vuexy Admin Template)
- ApexCharts

## Instalasi

```bash
# Clone repository
git clone https://github.com/BimaFdilana/laundry-Laravel.git
cd laundry-Laravel

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Setup database di .env lalu migrate
php artisan migrate

# Jalankan server
php artisan serve
```

## Role & Akses

| Role | Akses |
|------|-------|
| SuperAdmin | Semua fitur, data keuangan, laporan, kelola admin/karyawan |
| Admin | Transaksi, pelayanan, customer, piutang, laporan |
| Customer | Lihat transaksi, paket, gift |

## Lisensi

MIT License
