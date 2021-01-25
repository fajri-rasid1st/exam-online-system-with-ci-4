<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Login Success" data-icon="success"></div>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h2 mb-4 text-gray-700">Examination | Home</h1>
    <div class="row">
        <div class="col-md">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Informasi Penting
                    </h5>
                </div>
                <div class="card-body">
                    Diharapkan bagi user yang baru mendaftar agar segera melengkapi
                    data diri di menu "Edit Profile". Peserta tidak dapat melakukan
                    <i>try out</i> sebelum melengkapi data diri.
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>