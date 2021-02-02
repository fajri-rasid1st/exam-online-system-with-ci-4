<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Login Success" data-icon="success"></div>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="mb-4 text-gray-700">Examination | Home</h1>
    <div class="row mb-2">
        <div class="col-md">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Informasi Penting
                    </h6>
                </div>
                <div class="card-body">
                    Diharapkan bagi user yang baru mendaftar agar segera melengkapi
                    data diri di menu "Edit Profile". Peserta tidak dapat melakukan
                    <i>try out</i> sebelum melengkapi data diri.
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-4 text-gray-700">Silahkan pilih paket <i>Try Out</i> yang telah disediakan :</h5>

    <div class="row mb-4">
        <?php if (empty($exams)) : ?>
            <div class="col-md">
                <div class="text-center mb-4">
                    <i class="far fa-frown fa-4x mb-3"></i>
                    <h5 class="mb-0 text-gray-700">
                        Upsss... Belum ada paket <i>Try Out</i> yang tersedia.
                    </h5>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($exams as $exam) : ?>
            <?php $colors = ['success', 'info', 'secondary', 'primary', 'danger', 'warning', 'dark']; ?>

            <?php $color = $colors[rand(0, 6)] ?>

            <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                <div class="card card-exam border-left-<?= $color; ?> h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center justify-content-between">
                            <div class="col-md-10 text-left">
                                <div class="text-xs font-weight-bold text-<?= $color; ?> text-uppercase mb-1">
                                    <?= $exam['title']; ?>
                                </div>
                                <div class="h6 font-weight-bold text-gray-800 mb-2">
                                    <?= date('l, d F Y h:i A', strtotime($exam['implement_date'])); ?>
                                </div>
                                <a href="<?= base_url('exam_detail/' . $exam['id'] . '/' . $color); ?>" role="button" class="btn btn-sm btn-<?= $color; ?> btn-icon-action badge py-1 px-2">
                                    Lihat detail
                                </a>
                            </div>
                            <div class="col-md-2 text-right">
                                <i class="fas fa-clipboard-list fa-4x rotate-15 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection(); ?>