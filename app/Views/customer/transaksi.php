<?= $this->extend('templates/customer/index') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <!-- ANCHOR SIDEBAR -->
        <div class="col-2 mb-3">
            <div class="sidebar rounded-3 shadow-lg mt-3 py-4 pe-3 ps-3">
                <div class="row">
                    <div class="col text-center">
                        <img src="<?= base_url($akun['avatar']); ?>" alt="" class="foto-avatar shadow">
                        <p class="mt-1">
                        <h6><?= $akun['nama'] ?></h6>
                        <span><?= $akun['email'] ?></span>
                        </p>
                    </div>
                </div>
                <hr class="mt-0">
                <div class="row">
                    <div class="col">
                        <h6>Profil Saya</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <ul class="list-pengaturan-akun">
                            <a href="<?= base_url('customer/akun') ?>">
                                <li class="list-akun rounded-1 <?= ($segment2 == 'akun') ? 'selected-pengaturan-akun' : ''; ?>">
                                    Akun
                                </li>
                            </a>
                            <a href="<?= base_url('customer/transaksi/all') ?>">
                                <li class="list-akun rounded-1 <?= ($segment2 == 'transaksi') ? 'selected-pengaturan-akun' : ''; ?>">
                                    Riwayat Transaksi
                                </li>
                            </a>
                            <a href="<?= base_url('logout') ?>">
                                <li class="list-akun-keluar rounded-1">
                                    Keluar
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>
                <hr class="mt-0">
            </div>
        </div>
        <!-- ANCHOR CONTENT PEMBELIAN -->
        <div class="col-10">
            <!-- ANCHOR NAVBAR -->
            <div class="row text-center content rounded-3 shadow-lg mt-3 py-3 pe-4 ps-4">
                <ul class="list-group list-group-horizontal">
                    <div class="col">
                        <a href="<?= base_url('customer/transaksi/all') ?>">
                            <li class="list-group-item navbar-transaksi 
                                    <?php if ($segment3 === 'all') {
                                        echo 'navbar-transaksi-active';
                                    } ?>">
                                Semua Transaksi
                            </li>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url('customer/transaksi/belum-dibayar') ?>">
                            <li class="list-group-item navbar-transaksi
                                    <?php if ($segment3 === 'belum-dibayar') {
                                        echo 'navbar-transaksi-active';
                                    } ?>">
                                Belum Dibayar
                            </li>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url('customer/transaksi/sedang-dikerjakan') ?>">
                            <li class="list-group-item navbar-transaksi
                                    <?php if ($segment3 === 'sedang-dikerjakan') {
                                        echo 'navbar-transaksi-active';
                                    } ?>">
                                Sedang Dikerjakan
                            </li>
                        </a>
                    </div>
                    <div class="col">
                        <a href="<?= base_url('customer/transaksi/selesai') ?>">
                            <li class="list-group-item navbar-transaksi
                                    <?php if ($segment3 === 'selesai') {
                                        echo 'navbar-transaksi-active';
                                    } ?>">
                                Selesai
                            </li>
                        </a>
                    </div>
                </ul>
            </div>
            <?php
            for ($i = 0; $i < count($transaksi); $i++) { ?>
                <div class="row content rounded-3 shadow-lg mt-3 py-3 pe-3 ps-3">
                    <div class="col">

                        <div class="row">
                            <div class="col">
                                <p><?= date_format(date_create($transaksi[$i]['tanggal_transaksi']), 'd M Y') ?> |
                                    <?php
                                    if ($transaksi[$i]['status'] === 'Selesai') {
                                        echo "<span class='badge badge-selesai'>Selesai</span>";
                                    } else {
                                        echo "<span class='badge badge-proses'>On Going</span>";
                                    } ?> |
                                    <?php
                                    if ($transaksi[$i]['status_transfer'] === 'Selesai') {
                                        echo "<span class='badge badge-selesai'>Selesai</span>";
                                    } else {
                                        echo "<span class='badge badge-belum'>Belum Dibayar</span>";
                                    } ?> |
                                    <?= $transaksi[$i]['id'] ?>
                                </p>
                                <h6><i class="bi bi-pencil-fill"></i> <?= $transaksi[$i]['nama_designer']; ?></h6>
                                <img src="<?= base_url($transaksi[$i]['gambar1']) ?>" alt="" class="gambar-transaksi rounded-3 shadow-sm">
                            </div>
                            <div class="col border-start ps-3">
                                <h5><?= $transaksi[$i]['judul'] ?></h5>
                                <h6><?= $transaksi[$i]['kategori'] ?></h6>
                                <p><?= $transaksi[$i]['jumlah'] ?> Unit</p>
                                <p class="text-end">Total Harga</p>
                                <h3 class="text-end">Rp<?= number_format($transaksi[$i]['total'], 2, ',', '.') ?></h3>
                                <a href="<?= base_url('customer/transaksi/detail/' . $transaksi[$i]['id']) ?>">
                                    <button class="btn-transaksi mt-2 rounded-2">Detail</button></a>
                            </div>
                        </div>

                    </div>


                </div>
            <?php }
            ?>
            <?php
            if (count($transaksi) === 0) {
                echo "<div class='alert alert-danger alert-data-kosong text-center' role='alert'>Data Tidak Ditemukan !!!</div>";
            }
            ?>
        </div>
        <div class="row">
            <div class="col">
                <!-- ANCHOR PAGINATION -->
                <div class="row">
                    <div class="col mt-4">
                        <?= $pager->links('transaksiCustomer', 'custom_pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>