<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


    <div class="row">

        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
            <form action="<?= base_url('user/gantipassword'); ?>" method="post">
                <div class="form-group">
                    <label for="psekarang">Password Sekarang</label>
                    <input type="password" class="form-control" id="psekarang" name="psekarang">
                    <?= form_error('psekarang', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="psbaru1">Password Baru</label>
                    <input type="password" class="form-control" id="psbaru1" name="psbaru1">
                    <?= form_error('psbaru1', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="psbaru2">Ulangi Password Baru</label>
                    <input type="password" class="form-control" id="psbaru2" name="psbaru2">
                    <?= form_error('psbaru2', '<small class="text-danger pl-3">', '</small>'); ?>

                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Ganti Password

                    </button>
                </div>
            </form>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->