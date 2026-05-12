<div class="modal fade" id="edit_paket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Edit Paket Laundry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditPaket" method="POST">
                    @csrf
                    @method('PUT') <!-- HTTP Method PUT -->

                    <input type="hidden" name="id_paket" id="edit_id_paket">

                    {{-- Kg Paket --}}
                    <div class="form-group">
                        <label for="edit_kg" class="control-label">Kg Paket:</label>
                        <input type="text" name="kg" id="edit_kg" class="form-control" required>
                    </div>

                    {{-- Harga --}}
                    <div class="form-group">
                        <label for="edit_harga" class="control-label">Harga:</label>
                        <input type="number" name="harga" id="edit_harga" class="form-control" required>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group">
                        <label for="edit_kategori" class="control-label">Kategori:</label>
                        <select name="kategori" id="edit_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($hargas->unique('jenis') as $harga)
                                <option value="{{ $harga->jenis }}">{{ $harga->jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
