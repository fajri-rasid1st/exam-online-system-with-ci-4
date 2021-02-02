<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <h2 class="mb-4 text-gray-700">Exam List</h2>

    <div class="row mb-4">
        <div class="col-xl-12 col-md-12 col-sm-12 px-4">
            <?php if (empty($exams)) : ?>
                <div class="text-center mb-4">
                    <i class="far fa-frown fa-4x mb-3"></i>
                    <h5 class="mb-0 text-gray-700">
                        Upsss... Anda belum mendaftar pada paket <i>Try Out</i> manapun.
                    </h5>
                </div>
            <?php else : ?>
                <h5 class="mb-0 text-gray-700">
                    Berikut adalah daftar paket <i>Try Out</i> yang telah anda daftari :
                </h5>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-9 col-md-9 col-sm-9">
            <div class="row d-flex flex-column">
                <?php foreach ($exams as $exam) : ?>
                    <?php if ($exam['status'] == $exam_status[1]) : ?>
                        <?php $color = 'primary'; ?>
                    <?php elseif ($exam['status'] == $exam_status[2]) : ?>
                        <?php $color = 'success'; ?>
                    <?php else : ?>
                        <?php $color = 'dark'; ?>
                    <?php endif; ?>

                    <div class="col-xl col-md col-sm mb-4">
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

                                        <button type="button" data-id="<?= $exam['id']; ?>" id="btn-detail-enroll-exam" class="btn btn-sm btn-<?= $color; ?> btn-icon-action badge py-1 px-2">
                                            Lihat Detail
                                        </button>
                                    </div>

                                    <div class="col-md-2 text-right">
                                        <i class="fas fa-clipboard-list fa-4x rotate-15 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer" id="detail-enroll-exam-<?= $exam['id']; ?>" style="display: none;">
                                <div class="row my-3">
                                    <div class="col-md">
                                        <table class="table table-bordered table-secondary table-striped">
                                            <tbody class="text-gray-800" style="font-size: 0.9rem;">
                                                <tr>
                                                    <th scope="row">Nama Paket</th>
                                                    <td><?= $exam['title']; ?></td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Jadwal</th>
                                                    <td><?= date('l, d F Y h:i A', strtotime($exam['implement_date'])); ?></td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Status</th>
                                                    <td><?= $exam['status']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Durasi</th>
                                                    <td><?= $exam['duration']; ?> Menit</td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Jumlah Soal</th>
                                                    <td><?= $exam['total_question']; ?> Soal</td>
                                                </tr>

                                                <tr>
                                                    <th scope="row">Keterangan Score</th>
                                                    <td>
                                                        Benar = <?= $exam['score_per_right_answer']; ?>,
                                                        Salah = <?= $exam['score_per_wrong_answer']; ?>,
                                                        Kosong = <?= $exam['score_per_empty_answer']; ?>.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-between align-items-center mb-3">
                                    <div class="col-md-9 text-left">
                                        <?php if ($exam['status'] == $exam_status[1]) : ?>
                                            <small class="text-danger mb-0">
                                                <sup>*</sup> Try out belum dimulai. Tunggu hingga waktu yang
                                                ditetapkan, lalu refresh halaman.
                                            </small>
                                        <?php elseif ($exam['status'] == $exam_status[3]) : ?>
                                            <small class="text-danger mb-0">
                                                <sup>*</sup> Jika tombol "Lihat Hasil" belum muncul, silahkan
                                                refresh halaman.
                                            </small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-3 text-right">
                                        <?php if ($exam['status'] == $exam_status[1]) : ?>
                                            <button type="button" class="btn btn-<?= $color; ?>" data-toggle="modal" data-target="#confirmStartModal">
                                                Lihat Aturan
                                            </button>
                                        <?php elseif ($exam['status'] == $exam_status[2]) : ?>
                                            <a href="<?= base_url('exam_view/' . user()->id . '?page=user_exam_view&code=' . $exam['code']); ?>" role="button" class="btn btn-<?= $color; ?>">
                                                Kerjakan Sekarang
                                            </a>
                                        <?php else : ?>
                                            <a href="<?= base_url('exam_result/' . user()->id . '?code=' . $exam['code']); ?>" role="button" class="btn btn-<?= $color; ?>">
                                                Lihat Hasil
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-xl-3 col-md-3 col-sm-3">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-gray-700">Keterangan</h6>
                </div>

                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md">
                            <button class="btn btn-primary"></button> = Belum dimulai
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <button class="btn btn-success"></button> = Sedang berlangsung
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <button class="btn btn-dark"></button> = Telah selesai
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Start Modal-->
    <div class="modal fade" id="confirmStartModal" tabindex="-1" role="dialog" aria-labelledby="confirmStartLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmStartLabel">Aturan Pelaksanaan Try Out</h5>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3 font-weight-bold text-uppercase text-center">[Aturan]</h5>
                            <table class="table table-borderless">
                                <tbody class="text-gray-800" style="font-size: 0.9rem;">
                                    <tr>
                                        <th class="pb-0">-</th>
                                        <td class="pb-0">
                                            <h6 class="mb-2">
                                                Biasakan berdo'a terlebih dahulu sebelum melaksanakan ujian.
                                            </h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="pb-0">-</th>
                                        <td class="pb-0">
                                            <h6 class="mb-2">
                                                Soal berupa pilihan ganda dengan 6 jumlah pilihan yang
                                                terdiri dari 5 pilihan jawaban dan 1 pilihan jawaban kosong.
                                            </h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="pb-0">-</th>
                                        <td class="pb-0">
                                            <h6 class="mb-2">
                                                Anda dapat memilih jawaban kosong jika ingin mengosongkan
                                                jawaban (poin jawaban kosong berbeda dengan jawaban salah).
                                            </h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="pb-0">-</th>
                                        <td class="pb-0">
                                            <h6 class="mb-2">
                                                Setelah selesai menjawab semua pertanyaan, anda bisa keluar dari
                                                aplikasi ini (jawaban secara otomatis ter-submit)
                                            </h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="pb-0">-</th>
                                        <td class="pb-0">
                                            <h6 class="mb-2">
                                                Semoga beruntung yaaa...
                                            </h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Mengerti!</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>