<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">

        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
            <div class="form-group">
                <label for="ceat">Pendaftar</label>
                <input type="text" class="form-control" id="ceat" name="ceat">
                <?= form_error('ceat', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>
            <div class="form-group">
                <label for="muna">Masukkan HDSN/MAC/APALAH ITU</label>
                <input type="text" class="form-control" id="muna" name="muna">
                <?= form_error('muna', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>
            <div class="form-group">
                <label for="waktu">Masukkan Hari</label>
                <input type="date" class="form-control" id="waktu" name="waktu">
                <?= form_error('waktu', '<small class="text-danger pl-3">', '</small>'); ?>

            </div>
            <div class="form-group">
                <label for="pilihjenis">Pilih Model Cheat</label>
                <select class="custom-select" id="inputGroupSelect01" id="pilihjenis" name="pilihjenis">
                    <option selected>Pilih</option>
                    <option value="1">LostSaga</option>
                    <option value="2">PointBlank</option>
                </select>
                <?= form_error('pilihjenis', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#HasilAkhir">Daftarkan

                </button>
            </div>
            </form>
        </div>
    </div>



</div>
<!-- /.container-fluid -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="HasilAkhir" tabindex="-1" role="dialog" aria-labelledby="HasilAkhirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="HasilAkhirLabel">Data Anda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End of Main Content -->