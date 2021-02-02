<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- breadcrumb nav -->
    <nav aria-label="breadcrumb" class="breadcrumb-result">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hasil : <?= $exam["title"]; ?></li>
        </ol>
    </nav>
    <h1 class="h2 mb-4 text-gray-700 title-result">Hasil : <?= $exam['title']; ?></h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow bg-light mb-4 p-2 border-left-dark">
                <div class="row align-items-center">
                    <div class="col-md">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light d-flex align-content-center">
                                    <h4 class="card-title d-inline-block"><?= $exam['title']; ?></h4>
                                    <div>
                                        <span class="ml-3 py-1 badge badge-dark">
                                            <?= $exam['status']; ?>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <div class="row d-flex flex-column">
                                <div class="col-md">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="font-weight: 600;">Nama Lengkap</th>
                                                    <td><?= user()->fullname; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600;">Email</th>
                                                    <td><?= user()->email; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600;">No. Telepon</th>
                                                    <td><?= user()->phone_number; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600;">Alamat</th>
                                                    <td><?= user()->address; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="font-weight: 600;">Status Kehadiran</th>
                                                    <td><?= $attendance == "Attend" ? "Hadir" : "Tidak Hadir"; ?></td>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md">
                                    <div class="table-responsive">
                                        <table class="table table-secondary table-bordered table-striped">
                                            <thead class="thead-dark text-center">
                                                <tr>
                                                    <th scope="col" rowspan="2" style="font-weight: 600; vertical-align: middle;">
                                                        Tipe Soal
                                                    </th>
                                                    <th scope="col" rowspan="2" style="font-weight: 600; vertical-align: middle;">
                                                        Jumlah Soal
                                                    </th>
                                                    <th scope="col" colspan="3" style="font-weight: 600; vertical-align: middle;">
                                                        Keterangan
                                                    </th>
                                                    <th scope="col" rowspan="2" style="font-weight: 600; vertical-align: middle;">
                                                        Score
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col" style="font-weight: 600;">Benar</th>
                                                    <th scope="col" style="font-weight: 600;">Salah</th>
                                                    <th scope="col" style="font-weight: 600;">Kosong</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php $i = 0; ?>

                                                <?php $total = [
                                                    "scores"    => 0,
                                                    "questions" => 0,
                                                    "right"     => 0,
                                                    "wrong"     => 0,
                                                    "empty"     => 0,
                                                ]; ?>

                                                <?php foreach ($exam_result as $result) : ?>
                                                    <?php $total["scores"] += $result["scores"]; ?>

                                                    <?php $total["questions"] += $result["total_question"]; ?>

                                                    <tr>
                                                        <td><?= $result["types"]; ?></td>

                                                        <td class="text-center"><?= $result["total_question"]; ?></td>

                                                        <td class="text-center">
                                                            <?php if (isset($answer_info[0][$i])) : ?>
                                                                <?php $data = [
                                                                    "types"          => $answer_info[0][$i]["types"],
                                                                    "total_question" => $answer_info[0][$i]["total_question"]
                                                                ] ?>

                                                                <?php if ($answer_info[0][$i]["types"] == $result["types"]) : ?>
                                                                    <?php $total["right"] += $answer_info[0][$i]["total_question"] ?>

                                                                    <?= $answer_info[0][$i]["total_question"] ?>
                                                                <?php else : ?>
                                                                    <?php array_push($answer_info[0], $data); ?>

                                                                    <?= 0; ?>
                                                                <?php endif; ?>
                                                            <?php else : ?>
                                                                <?= 0; ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php if (isset($answer_info[1][$i])) : ?>
                                                                <?php $data = [
                                                                    "types"          => $answer_info[1][$i]["types"],
                                                                    "total_question" => $answer_info[1][$i]["total_question"]
                                                                ] ?>

                                                                <?php if ($answer_info[1][$i]["types"] == $result["types"]) : ?>
                                                                    <?php $total["wrong"] += $answer_info[1][$i]["total_question"] ?>

                                                                    <?= $answer_info[1][$i]["total_question"] ?>
                                                                <?php else : ?>
                                                                    <?php array_push($answer_info[1], $data); ?>

                                                                    <?= 0; ?>
                                                                <?php endif; ?>
                                                            <?php else : ?>
                                                                <?= 0; ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <?php if (isset($answer_info[2][$i])) : ?>
                                                                <?php $data = [
                                                                    "types"          => $answer_info[2][$i]["types"],
                                                                    "total_question" => $answer_info[2][$i]["total_question"]
                                                                ] ?>

                                                                <?php if ($answer_info[2][$i]["types"] == $result["types"]) : ?>
                                                                    <?php $total["empty"] += $answer_info[2][$i]["total_question"] ?>

                                                                    <?= $answer_info[2][$i]["total_question"] ?>
                                                                <?php else : ?>
                                                                    <?php array_push($answer_info[2], $data); ?>

                                                                    <?= 0; ?>
                                                                <?php endif; ?>
                                                            <?php else : ?>
                                                                <?= 0; ?>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td class="text-center"><?= $result["scores"]; ?></td>
                                                    </tr>

                                                    <?php $i++; ?>
                                                <?php endforeach; ?>

                                                <tr>
                                                    <th scope="col">Total</th>
                                                    <th scope="col" class="text-center"><?= $total["questions"]; ?></th>
                                                    <th scope="col" class="text-center"><?= $total["right"]; ?></th>
                                                    <th scope="col" class="text-center"><?= $total["wrong"]; ?></th>
                                                    <th scope="col" class="text-center"><?= $total["empty"]; ?></th>
                                                    <th scope="col" class="text-center"><?= $total["scores"]; ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 my-5 text-center print-action-area">
                        <div class="exam-icon mb-5">
                            <i class="fas fa-clipboard-list rotate-15 text-gray-300" style="font-size: 13rem;"></i>
                        </div>

                        <div class="exam-action">
                            <button type="button" class="btn btn-lg btn-dark" onclick="window.print();">
                                Print Hasil PDF
                                <i class="fas fa-print ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>