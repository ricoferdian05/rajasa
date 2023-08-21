<div class="row">
    <!-- ANCHOR CONTENT BODY -->
    <div class="col content ms-2 mt-3 ps-3 pt-3 pb-3 rounded-3 shadow">
        <div class="row mb-3">
            <form class="" role="search" action="" method="get">
                <div class="input-group shadow-sm rounded-2">
                    <input type="text" name="keywordCustomer" class="form-control search-default" placeholder="Cari Nama Customer" value="<?= $keywordCustomer; ?>" style="font-size: 10px;">
                    <button type="submit" class="input-group-text logo-search" id="basic-addon2"><i class="bi bi-search" style="font-size: 10px;"></i></button>
                </div>
            </form>
            <div class="col">
                <table class="table table-transaksi caption-top">
                    <caption>Total data: <?= $jumlahCustomer; ?></caption>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Id Customer</th>
                            <th scope="col">Nama Customer</th>
                            <th scope="col">Username</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Hp</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($customer) > 0) {
                            $nomor = 0 + (10 * ($urutanCustomer - 1));
                            for ($i = 0; $i < count($customer); $i++) { ?>
                                <tr>
                                    <td><?= $nomor += 1 ?></td>
                                    <td><?= $customer[$i]['id'] ?></td>
                                    <td>
                                        <img class="table-avatar me-2 rounded-circle" src="<?= base_url($customer[$i]['avatar']); ?>" alt="avatar-akun">
                                        <?= $customer[$i]['nama'] ?>
                                    </td>
                                    <td><?= $customer[$i]['username'] ?></td>
                                    <td><?= $customer[$i]['alamat'] ?></td>
                                    <td><?= $customer[$i]['no_hp'] ?></td>
                                    <td>
                                        <button class="btn-hapus" onclick="hapus('<?= base_url('admin/data-master/customer/hapus/' . $customer[$i]['id']); ?>')">Hapus</button>
                                    </td>
                                </tr>
                        <?php }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if (count($customer) === 0) {
                    echo "<div class='alert alert-danger alert-data-kosong text-center' role='alert'>Data Tidak Ditemukan !!!</div>";
                }
                ?>
                <!-- ANCHOR PAGINATION -->
                <div class="row">
                    <div class="col mt-2">
                        <?= $pagerCustomer->links('akunCustomer', 'custom_pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function hapus(link) {
        Swal.fire({
            title: 'Hapus',
            text: 'Ingin menghapus akun Customer ini? \n Semua data yang berhubungan dengan akun ini akan terhapus',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
            }
        })
    }
</script>