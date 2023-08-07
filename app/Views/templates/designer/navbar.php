<div class="col">
    <div class="tanggal p-2 rounded-5 text-center">
        <?php
        date_default_timezone_set('Asia/Jakarta');
        echo date("d-m-Y")
        ?>
    </div>
</div>
<div class="col d-flex justify-content-end">
    <a href="<?= base_url('chat') ?>" class="p-2 chat rounded-3" target="_blank">
        <i class="bi bi-chat-right-text"></i>
    </a>
</div>