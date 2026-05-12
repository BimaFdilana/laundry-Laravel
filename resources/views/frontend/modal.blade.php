<div class="modal_status">
    <div class="modal_window">
        <!-- Header Modal -->
        <div class="modal-header-custom">
            <div class="title-wrapper">
                <i class="fas fa-receipt text-emerald mr-2"></i>
                <span class="title">Detail Pesanan</span>
            </div>
            <button class="close-icon" onclick="close_dlgs()">&times;</button>
        </div>

        <div class="modal-body-custom">
            <!-- Customer Info -->
            <div class="info-card mb-3">
                <p class="label-text">Nama Customer</p>
                <p class="value-text" id="customer">-</p>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="info-card">
                        <p class="label-text">Tgl Transaksi</p>
                        <p class="value-text" id="tgl_transaksi">-</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-card">
                        <p class="label-text">Status Laundry</p>
                        <!-- Status Badge -->
                        <div class="status-badge" id="status_order">Memproses...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer-custom mt-4">
            <button class="btn-close-modal" onclick="close_dlgs()">Selesai</button>
        </div>
    </div>
</div>

<style>
    /* Backdrop Modal */
    .modal_status {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(38, 70, 83, 0.85);
        /* Deep Teal Semi Transparan */
        backdrop-filter: blur(8px);
        z-index: 99999;
    }

    /* Window Modal */
    .modal_window {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 450px;
        padding: 35px;
        background-color: #ffffff;
        border-radius: 30px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(15, 185, 177, 0.2);
    }

    /* Header Modal */
    .modal-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f7f6;
        padding-bottom: 15px;
    }

    .title-wrapper .title {
        font-size: 20px;
        font-weight: 800;
        color: #264653;
    }

    .text-emerald {
        color: #0fb9b1;
    }

    .close-icon {
        background: none;
        border: none;
        font-size: 28px;
        color: #bdc3c7;
        cursor: pointer;
        transition: 0.3s;
        line-height: 1;
    }

    .close-icon:hover {
        color: #e74c3c;
    }

    /* Content Cards */
    .info-card {
        background: #f8fafb;
        padding: 15px;
        border-radius: 15px;
        border: 1px solid #edf2f4;
    }

    .label-text {
        font-size: 11px !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #7f8c8d !important;
        font-weight: 700;
        margin-bottom: 5px !important;
    }

    .value-text {
        font-size: 16px !important;
        color: #264653 !important;
        font-weight: 800;
        margin-bottom: 0 !important;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #0fb9b1;
        color: white;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        width: 100%;
    }

    /* Footer Button */
    .btn-close-modal {
        width: 100%;
        padding: 15px;
        background: #264653;
        /* Dark Teal */
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        font-size: 15px;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(38, 70, 83, 0.2);
    }

    .btn-close-modal:hover {
        background: #0fb9b1;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(15, 185, 177, 0.3);
    }

    /* Responsivitas */
    @media (max-width: 576px) {
        .modal_window {
            padding: 25px;
        }
    }
</style>