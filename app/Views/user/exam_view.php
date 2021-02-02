<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <h1 class="h2 mb-4 text-gray-700">Online Examination</h1>
    <div class="row mb-2">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex align-items-center py-4">
                    <i class="fas fa-clipboard-list text-gray-700 fa-2x mr-3"></i>
                    <h5 class="m-0 text-uppercase text-gray-700" style="font-weight: 500;"><?= $title; ?></h5>
                </div>
                <div class="card-body bg-light">
                    <!-- question view -->
                    <div class="mb-2 px-2" id="single-question-area"></div>
                </div>
                <div class="card-footer py-3">
                    <small class="m-0 font-weight-normal text-danger">
                        <sup>*</sup> Pilih [Jawaban kosong] jika ingin mengosongkan jawaban.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <a href="#collapseNav" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseNav">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bars text-gray-700 mr-2"></i>
                        <h6 class="m-0 text-uppercase text-gray-700" style="font-weight: 500;">Navigasi Soal</h6>
                    </div>
                </a>
                <div class="collapse show" id="collapseNav">
                    <div class="card-body bg-light">
                        <!-- question navigation -->
                        <div class="row mb-2 px-2">
                            <div class="col-md" id="nav-question-area"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <a href="#collapseTimer" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseTimer">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-gray-700 mr-2"></i>
                        <h6 class="m-0 text-uppercase text-gray-700" style="font-weight: 500;">Sisa Waktu</h6>
                    </div>
                </a>
                <div class="collapse show" id="collapseTimer">
                    <div class="card-body bg-light">
                        <!-- question timer -->
                        <div class="row">
                            <div class="col-md" id="timer-question-area">
                                <div class="exam-timer" data-timer="<?= $exam_time_remaining; ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <a href="#collapseProfile" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseProfile">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-address-card text-gray-700 mr-2"></i>
                        <h6 class="m-0 text-uppercase text-gray-700" style="font-weight: 500;">Profile Data</h6>
                    </div>
                </a>
                <div class="collapse show" id="collapseProfile">
                    <div class="card-body bg-light">
                        <!-- profile user -->
                        <div class="row mb-2 px-2">
                            <div class="col-md" id="profile-user-area">
                                <div class="user-image-container text-center mb-4">
                                    <img class="user-image img-thumbnail" src="<?= base_url('img/profile/' . user()->profile_pict); ?>" alt="<?= user()->profile_pict; ?>">
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-secondary table-striped">
                                        <tbody class="text-gray-800" style="font-size: 0.8rem;">
                                            <tr>
                                                <th scope="row">Nama</th>
                                                <td><?= user()->fullname; ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td><?= user()->email; ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">No. Telp</th>
                                                <td><?= user()->phone_number; ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Jenis Kelamin</th>
                                                <td><?= user()->gender == "Male" ? "Laki-laki" : "Perempuan"; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>