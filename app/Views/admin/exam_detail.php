<?php if ($exam['status'] == $exam_status[0]) : ?>
    <?= $color = 'secondary'; ?>
<?php elseif ($exam['status'] == $exam_status[1]) : ?>
    <?= $color = 'primary'; ?>
<?php elseif ($exam['status'] == $exam_status[2]) : ?>
    <?= $color = 'success'; ?>
<?php else : ?>
    <?= $color = 'dark'; ?>
<?php endif; ?>

<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- breadcrumb nav -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('exam'); ?>">Exam List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Exam Detail</li>
        </ol>
    </nav>
    <h1 class="h2 mb-4 text-gray-700">Exam Detail</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light mb-4 p-2 border-left-<?= $color; ?>">
                <div class="row align-items-center">
                    <div class="col-md">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light d-flex align-content-center">
                                    <h4 class="card-title d-inline-block"><?= $exam['title']; ?></h4>
                                    <div>
                                        <span class="ml-2 py-1 badge badge-<?= $color; ?>">
                                            <?= $exam['status']; ?>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-group-item bg-light">
                                    <label>Date Schedule</label>
                                    <div class="pl-4"><?= date('l, d F Y (H:i:s)', strtotime($exam['implement_date'])); ?></div>
                                </li>

                                <li class="list-group-item bg-light">
                                    <label>Duration</label>
                                    <div class="pl-4"><?= $exam['duration']; ?> Minutes</div>
                                </li>

                                <li class="list-group-item bg-light">
                                    <label>Total Question</label>
                                    <div class="pl-4"><?= $exam['total_question']; ?> Questions</div>
                                </li>

                                <li class="list-group-item bg-light">
                                    <label>Description Score</label>
                                    <div class="pl-4">
                                        Correct : <?= $exam['score_per_right_answer']; ?>,
                                        Wrong : <?= $exam['score_per_wrong_answer']; ?>,
                                        Empty : <?= $exam['score_per_empty_answer']; ?>.
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>