<?= $this->extend('templates/admin/index') ?>

<?= $this->section('content') ?>

<div class="container pt-3">
    <div class="row">
        <div class="col-2 sidebar pt-3 pb-3 rounded-3 shadow">
            <!-- ANCHOR SIDEBAR -->
            <?= $this->include('templates/admin/sidebar') ?>
        </div>
        <div class="col-10">
            <div class="row">
                <div class="col navbar ms-2 ps-3 pt-3 pb-3 rounded-3 shadow">
                    <!-- ANCHOR NAVBAR -->
                    <?= $this->include('templates/admin/navbar') ?>
                </div>
            </div>
            <div class="row">
                <!-- ANCHOR CONTENT -->
                <div class="col content ms-2 mt-3 mb-3 ps-3 pe-3 pt-4 pb-4 rounded-3 shadow">
                    <div class="row">
                        <div class="col text-center">
                            <h3>Pengaturan Akun</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <form action="<?= base_url('admin/saveAkun/' . $akun['id']) ?>" method="post" enctype="multipart/form-data" id="myForm">
                                <center class="mb-3">
                                    <img class="avatar-form mb-2 rounded-circle" src="<?= base_url($akun['avatar']) ?>" alt="" id="previewAvatar">
                                    <input class="form-control input-avatar" type="file" name="avatar" id="avatar" onchange="previewFile(this)" accept="image/*">
                                </center>
                                <div class="row mb-3">
                                    <label for="inputNama" class="col-sm-2 col-form-label required">Nama Lengkap</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nama" required placeholder="Masukkan Nama Lengkap" value="<?= $akun['nama'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputUsername" class="col-sm-2 col-form-label required">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="username" id="inputUsername" required placeholder="Masukkan Username" value="<?= $akun['username'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label required">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="inputEmail" required placeholder="Masukkan Email" value="<?= $akun['email'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label required">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password" id="inputPassword" required placeholder="Masukkan Password" minlength="8" value="<?= $akun['password'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputAlamat" class="col-sm-2 col-form-label required">Alamat</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="alamat" id="inputAlamat" required placeholder="Masukkan Alamat" value="<?= $akun['alamat'] ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputNoHp" class="col-sm-2 col-form-label required">No. Handphone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="noHp" id="inputNoHp" required placeholder="Masukkan No. Handphone" value="<?= $akun['no_hp'] ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn-submit rounded-2 shadow">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function previewFile(input) {
        var file = $("#avatar").get(0).files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function() {
                $("#previewAvatar").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }

    }

    if (<?= json_encode(session()->getFlashdata('update_success')) ?>) {
        Swal.fire({
            title: 'Sukses',
            text: <?= json_encode(session()->getFlashdata('update_success')) ?>,
            icon: 'success',
        });
    } else if (<?= json_encode(session()->getFlashdata('update_error')) ?>) {
        Swal.fire({
            title: 'Gagal',
            text: <?= json_encode(session()->getFlashdata('update_error')) ?>,
            icon: 'error',
        });
    }
</script>
<?= $this->endSection() ?>