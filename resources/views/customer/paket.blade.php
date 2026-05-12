@extends('layouts.backend')
@section('title', 'Paket Laundry')
@section('style')
@endsection
@section('content')
    <div class="row justify-content-center">
        @foreach ($paketGrouped as $kategori => $pakets)
            <div class="col-12">
                <h3 class="text-center mb-2 mt-2">{{ $kategori }}</h3>
            </div>
            @foreach ($pakets as $paket)
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card text-center h-100">
                        <div
                            class="card-header d-flex flex-column
                        {{ $loop->iteration % 3 == 1 ? 'bg-primary' : ($loop->iteration % 3 == 2 ? 'bg-success' : 'bg-warning') }}
                        text-white">
                            <h2 class="kgpaket mb-1">{{ $paket->kg }} kg</h2>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h2 class="hargapaket text-bold-700 mb-2">Rp {{ number_format($paket->harga, 0, ',', '.') }}</h2>
                            <p class="flex-grow-1">Paket kategori {{ $paket->kategori }}</p>
                            <button
                                class="btn btn-pilih-paket {{ $loop->iteration % 3 == 1 ? 'btn-primary' : ($loop->iteration % 3 == 2 ? 'btn-success' : 'btn-warning') }} mt-auto"
                                data-package-kg="{{ $paket->kg }}" data-package-price="{{ $paket->harga }}"
                                data-package-category="{{ $paket->kategori }}">
                                Pilih Paket
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <!-- Modal Konfirmasi Pembelian -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Konfirmasi Pembelian</h5>
                </div>
                <div class="modal-body">
                    <p id="packageDetails"></p>
                    <h3 id="packagePrice" class="text-bold"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmPurchase">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pemberitahuan Pembayaran -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="alertLabel">Pemberitahuan</h5>
                </div>
                <div class="modal-body">
                    <p>Silakan lakukan pembayaran sebesar <strong id="alertPrice"></strong> untuk paket laundry <strong
                            id="alertPackage"></strong> ke rekening BSI 7309961662 a.n Nazrantika Sunarto</p>
                    <p>Hubungi Admin <a href="https://wa.me/6282284392025" target="_blank">https://wa.me/6282284392025</a>
                        untuk konfirmasi pembayaran.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".btn-pilih-paket");

            const purchaseModalElement = document.getElementById("purchaseModal");
            const alertModalElement = document.getElementById("alertModal");

            const purchaseModal = new bootstrap.Modal(purchaseModalElement);
            const alertModal = new bootstrap.Modal(alertModalElement);

            const confirmPurchaseButton = document.getElementById("confirmPurchase");
            const closeAlertButton = document.querySelector("#alertModal .btn-warning");
            const cancelButton = document.querySelector("#purchaseModal .btn-secondary");

            // Simpan data yang dipilih
            let selectedPackage = {};

            // Handle klik tombol Pilih Paket
            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const kg = button.getAttribute("data-package-kg");
                    const price = button.getAttribute("data-package-price");
                    const category = button.getAttribute("data-package-category");

                    selectedPackage = {
                        kg: kg,
                        price: price,
                        category: category
                    };

                    // Tampilkan ke modal
                    document.getElementById("packageDetails").innerText =
                        `Anda yakin ingin membeli paket laundry ${kg}?`;
                    document.getElementById("packagePrice").innerText =
                        `Rp ${parseInt(price).toLocaleString('id-ID')}`;
                    document.getElementById("alertPackage").innerText = kg;
                    document.getElementById("alertPrice").innerText =
                        `Rp ${parseInt(price).toLocaleString('id-ID')}`;

                    purchaseModal.show();
                });
            });

            // Klik Beli Sekarang
            confirmPurchaseButton.addEventListener("click", function() {
                fetch('/api/purchase-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            package_kg: selectedPackage.kg,
                            package_price: selectedPackage.price,
                            package_category: selectedPackage.category
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Gagal menyimpan data");
                        return response.json();
                    })
                    .then(data => {
                        purchaseModal.hide();
                        setTimeout(() => {
                            alertModal.show();
                        }, 500);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat memproses pembelian.");
                    });
            });

            // Tutup modal alert
            closeAlertButton.addEventListener("click", function() {
                alertModal.hide();
            });

            // Batal
            cancelButton.addEventListener("click", function() {
                purchaseModal.hide();
            });
        });
    </script>
@endsection
