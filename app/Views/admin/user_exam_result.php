<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- breadcrumb nav -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= base_url('exam'); ?>">Exam List</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('user_enroll?page=user_enroll&code=' . $_GET['code']); ?>">User Enroll List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
        </ol>
    </nav>
    <h1 class="h2 mb-4 text-gray-700">
        <?= $title; ?>
    </h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="table-title m-0"></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="user-exam-result-table" style="width: 100%;">
                            <thead class="bg-dark" style="color: #f0f5f9;">
                                <tr>
                                    <th scope="col" class="table-col">Image</th>
                                    <th scope="col" class="table-col">Question</th>
                                    <th scope="col" class="table-col">Type</th>
                                    <th scope="col" class="table-col">User Answer</th>
                                    <th scope="col" class="table-col">Right Answer</th>
                                    <th scope="col" class="table-col">Status</th>
                                    <th scope="col" class="table-col">Scores</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>