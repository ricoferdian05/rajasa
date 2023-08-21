<div class="row">
    <!-- ANCHOR CONTENT BODY -->
    <div class="col content ms-2 mt-3 ps-3 pt-3 pb-3 rounded-3 shadow">
        <div class="row">
            <div class="col">
                <table class="table table-transaksi caption-top">
                    <caption>Total data : <?= count($tipe) ?></caption>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Id Tipe</th>
                            <th scope="col">Tipe Akun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($tipe) > 0) {
                            $nomor = 0;
                            for ($i = 0; $i < count($tipe); $i++) { ?>
                                <tr>
                                    <td><?= $nomor += 1; ?></td>
                                    <td><?= $tipe[$i]['id_tipe'] ?></td>
                                    <td><?= $tipe[$i]['tipe'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                if (count($tipe) === 0) {
                    echo "<div class='alert alert-danger alert-data-kosong text-center' role='alert'>Data Tidak Ditemukan !!!</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
</script>