<?= $this->extend('templates/customer/index'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- JUMBOTRON -->
            <div class="container shadow-lg jumbotron rounded-3 mt-3 py-5 pe-5 ps-5">
                <div class="row">
                    <div class="col text-center">
                        <img src="<?= base_url('asset/website/photocopy.svg') ?>" alt="" width="">
                    </div>
                    <div class="col my-auto">
                        <h2 class="text-jumbotron">PRINT CALENDAR, WEDDING INVITATION, BROCHURE OR OTHERWISE?</h2>
                        <a href="" class="btn btn-print mt-2">PRINT NOW</a>
                    </div>
                </div>
            </div>
            <!-- END JUMBOTRON -->
        </div>
    </div>
</div>
<?= $this->endSection(); ?>