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
                            <a href="<?= base_url('customer/pembelian') ?>">
                                <li class="list-akun rounded-1 <?= ($segment2 == 'pembelian') ? 'selected-pengaturan-akun' : ''; ?>">
                                    Riwayat Pembelian
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
            <div class="content rounded-3 shadow-lg mt-3 py-3 pe-4 ps-4">
                <div class="row">
                    <div class="col">
                        <h4 class="mb-3">Riwayat Pembelian</h4>
                    </div>
                </div>
                <hr class="mt-0">
                <div class="row">
                    <div class="col">
                        <table class="table table-transaksi caption-top">
                            <thead class="border-bottom">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">ID Transaksi</th>
                                    <th scope="col">Tanggal Transaksi</th>
                                    <th scope="col">Tanggal Pengiriman</th>
                                    <th scope="col">Nama Pelanggan</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Total Harga</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Status Transfer</th>
                                    <th scope="col">Nama Designer</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($transaksi) > 0) {
                                    $nomor = 0 + (10 * ($urutan - 1));
                                    for ($i = 0; $i < count($transaksi); $i++) { ?>
                                        <tr class="
                                            <?php
                                            if ($transaksi[$i]['status_transfer'] === 'Belum') {
                                                echo 'baris-belum-transfer';
                                            } elseif ($transaksi[$i]['status'] === 'On Going' && $transaksi[$i]['status_transfer'] === 'Selesai') {
                                                echo 'baris-sedang-dikerjakan';
                                            }
                                            ?>">
                                            <td><?= $nomor += 1; ?></td>
                                            <td><?= $transaksi[$i]['id']; ?></td>
                                            <td><?= $transaksi[$i]['tanggal_transaksi']; ?></td>
                                            <td><?= $transaksi[$i]['tanggal_pengiriman']; ?></td>
                                            <td><?= $transaksi[$i]['nama_customer']; ?></td>
                                            <td><?= $transaksi[$i]['produk']; ?></td>
                                            <td><?= $transaksi[$i]['kategori']; ?></td>
                                            <td><?= $transaksi[$i]['jumlah']; ?></td>
                                            <td>Rp<?= number_format($transaksi[$i]['total'], 2, ',', '.'); ?></td>
                                            <td><?= $transaksi[$i]['status']; ?></td>
                                            <td><?= $transaksi[$i]['status_transfer']; ?></td>
                                            <td><?= $transaksi[$i]['nama_designer']; ?></td>
                                            <td><a href="<?= base_url('admin/transaksi/details/' . $transaksi[$i]['id']); ?>" class="btn-detail-transaksi">Detail</a></td>
                                        </tr>
                                <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        if (count($transaksi) === 0) {
                            echo "<div class='alert alert-danger alert-data-kosong text-center' role='alert'>Data Tidak Ditemukan !!!</div>";
                        }
                        ?>
                    </div>
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
    </div>
</div>

<?= $this->endSection() ?>