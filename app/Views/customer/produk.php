<?= $this->extend('templates/customer/index'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <!-- ANCHOR SIDEBAR -->
        <div class="col-2 mb-3">
            <div class="sidebar rounded-3 shadow-lg mt-3 py-4 pe-3 ps-3">
                <div class="row">
                    <div class="col">
                        <h5>Filter</h5>
                    </div>
                </div>
                <hr class="mt-0">
                <!-- ANCHOR FILTER KATEGORI -->
                <div class="row mt-2">
                    <div class="col">
                        <h6>Kategori</h6>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col">
                        <ul class="list-filter-kategori">
                            <?php foreach ($kategori as $k) : ?>
                                <li>
                                    <a href="" class="filter-kategori"><?= $k->kategori; ?></a>
                                    <!-- <input type="checkbox" name="kategori" id="kategori" class="form-check-input check-filter-kategori" value="<?= $k->id; ?>" onchange="checkKategori();">
                                    <span class="label-filter-kategori"><?= $k->kategori; ?></span> -->
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <hr class="mt-0">
                <!-- ANCHOR FILTER HARGA -->
                <div class="row mt-2">
                    <div class="col">
                        <h6>Harga</h6>
                    </div>
                </div>
                <form action="" method="post">
                    <div class="row mt-1">
                        <div class="col">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" id="label-harga">Rp</span>
                                <input type="text" class="form-control" name="hargaTerendah" id="input-harga-terendah" placeholder="Terendah" onkeyup="formatHargaRendah(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text" id="label-harga">Rp</span>
                                <input type="text" class="form-control" name="hargaTertinggi" id="input-harga-tertinggi" placeholder="Tertinggi" onkeyup="formatHargaTinggi(this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <button type="submit" class="btn-filter-harga rounded-1">Terapkan</button>
                        </div>
                    </div>
                </form>
                <hr class="mt-0">
            </div>
        </div>
        <!-- ANCHOR CONTENT -->
        <div class="col-10">
            <div class="content rounded-3 shadow-lg mt-3 py-3 pe-4 ps-4 ">
                <!-- ANCHOR KATEGORI -->
                <div class="row">
                    <div class="col">
                        <span class="label-jumlah-produk">
                            Menampilkan <span class="label-angka-produk"><?= $jumlahProduk ?></span> produk
                        </span>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <span class="label-urutkan">Urutkan : </span>
                        <form action="">
                            <div class="select-urutkan ms-2 rounded-2">
                                <select class="btn-urutkan rounded-2" name="urutkan">
                                    <option class="option-urutkan" value="terbaru" selected>Terbaru</option>
                                    <option class="option-urutkan" value="harga terendah">Harga Terendah</option>
                                    <option class="option-urutkan" value="harga tertinggi">Harga Tertinggi</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <!-- ANCHOR CONTENT -->
                <div class="row">
                    <?php for ($i = 0; $i < $jumlahProduk; $i++) { ?>
                        <div class="col-2 mb-3">
                            <a href="" class="produk">
                                <div class="card card-produk rounded-2">
                                    <img src="<?= base_url($produk[$i]->gambar1) ?>" class="card-img-top gambar-produk" alt="image">
                                    <div class="card-body card-body-produk rounded-bottom p-2">
                                        <h5 class="card-title card-title-harga mt-1">Rp<span class="harga-produk"><?= number_format($produk[$i]->harga, 2, ',', '.') ?></span></h5>
                                        <p class="card-text card-text-produk mt-2"><?= $produk[$i]->judul ?></p>
                                        <div class="footer-produk">
                                            <i class="bi bi-star-fill icon-rating"></i><span class="rating ms-1 me-1"><?= $produk[$i]->rating ?></span>|
                                            <span class="footer-terjual ms-1">Terjual <?= $produk[$i]->terjual ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <!-- ANCHOR PAGINATION -->
                <div class="row">
                    <div class="col mt-4">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination pagination-sm justify-content-center">
                                <li class="page-item">
                                    <a class="page-link pagination-link"><i class="bi bi-arrow-left"></i></a>
                                </li>
                                <li class="page-item"><a class="page-link pagination-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link pagination-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link pagination-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link pagination-link" href="#"><i class="bi bi-arrow-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>