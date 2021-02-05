<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Upload File Success" data-icon="success"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Unable To Upload File" data-icon="error"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("warning"); ?>' data-title="Exam Belum Terkunci" data-icon="warning"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("uncompleted"); ?>' data-title="Exam Belum Selesai" data-icon="warning"></div>
<div class="container-fluid">
    <h1 class="h2 mb-4 text-gray-700">Exam List</h1>
    <div class="alert alert-danger mb-4">
        <small class="d-block">
            <sup>*</sup> Untuk menambahkan file kunci jawaban, silahkan klik icon 'upload'
            lalu pilih file (pdf, doc, atau docx). Setelah itu, klik tombol 'upload'.
        </small>
        <small class="d-block">
            <sup>*</sup> Setelah file diupload, anda bisa mengunduhnya dengan mengklik icon
            pdf.
        </small>
        <small class="d-block">
            <sup>*</sup> File kunci jawaban hanya bisa diisi satu per satu.
        </small>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="table-title m-0">Exams Table</h5>
                    <button type="button" class="btn btn-primary" id="create-exam">
                        <i class="fas fa-plus mr-1"></i>
                        Create Exam
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="exams-table" style="width: 100%;">
                            <thead class="bg-dark" style="color: #f0f5f9;">
                                <tr>
                                    <th scope="col" class="table-col">ID</th>
                                    <th scope="col" class="table-col">Title</th>
                                    <th scope="col" class="table-col">Date Schedule</th>
                                    <th scope="col" class="table-col">Status</th>
                                    <th scope="col" class="table-col">Enrolled</th>
                                    <th scope="col" class="table-col">Scores</th>
                                    <th scope="col" class="table-col">Question</th>
                                    <th scope="col" class="table-col">Answer</th>
                                    <th scope="col" class="table-col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exam Modal -->
    <div class="modal fade" id="examModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="examModalLabel" aria-hidden="true">
        <form method="POST" id="exam-form">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="examModalLabel"></h5>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group row mb-2 mt-3">
                                <div class="col-md-6 mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" class="form-control form-control-user" id="title" placeholder="Title" spellcheck="false" autocomplete="off">
                                    <small class="invalid-title text-danger mb-0"></small>
                                </div>

                                <div class="col-md-6">
                                    <label for="schedule">Date Schedule</label>
                                    <input type="text" name="schedule" class="form-control form-control-user" id="schedule" placeholder="Date Schedule" spellcheck="false" autocomplete="off" readonly>
                                    <small class="invalid-schedule text-danger mb-0"></small>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="duration">Duration (in minutes)</label>
                                    <div class="field mb-2 d-flex">
                                        <span class="left-value">5</span>
                                        <input type="range" name="duration" id="duration" class="custom-range px-2" value="5" min="5" max="300" step="1">
                                        <span class="left-value">300</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="slider-value duration-value px-2">5</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="question">Total Question</label>
                                    <div class="field mb-2 d-flex">
                                        <span class="left-value">5</span>
                                        <input type="range" name="question" id="question" class="custom-range px-2" value="5" min="5" max="300" step="1">
                                        <span class="left-value">300</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="slider-value question-value px-2">5</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label for="right-answer">Score Per Right Answer</label>
                                    <div class="field mb-2 d-flex">
                                        <span class="left-value">+0</span>
                                        <input type="range" name="right-answer" id="right-answer" class="custom-range px-2" value="0" min="0" max="10" step="1">
                                        <span class="left-value">+10</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="slider-value right-answer-value px-2">0</span>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="wrong-answer">Score Per Wrong Answer</label>
                                    <div class="field mb-2 d-flex">
                                        <span class="left-value">-10</span>
                                        <input type="range" name="wrong-answer" id="wrong-answer" class="custom-range px-2" value="-10" min="-10" max="0" step="1">
                                        <span class="left-value">-0</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="slider-value wrong-answer-value px-2">-10</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="empty-answer">Score Per Empty Answer</label>
                                    <div class="field mb-2 d-flex">
                                        <span class="left-value">-10</span>
                                        <input type="range" name="empty-answer" id="empty-answer" class="custom-range px-2" value="-10" min="-10" max="10" step="1">
                                        <span class="left-value">+10</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="slider-value empty-answer-value px-2">-10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" id="exam-cancel-submit" type="button" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="exam-id" id="exam-id">
                        <input type="hidden" name="action" id="action" value="create">
                        <button type="submit" name="exam-submit" id="exam-submit" class="btn btn-dark"></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>