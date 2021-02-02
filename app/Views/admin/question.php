<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Upload File Success" data-icon="success"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Unable To Upload File" data-icon="error"></div>
<div class="container-fluid">
    <!-- breadcrumb nav -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= base_url('exam'); ?>">Exam List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Questions</li>
        </ol>
    </nav>
    <h1 class="h2 mb-4 text-gray-700">
        Question List
    </h1>
    <div class="alert alert-danger mb-4">
        <small class="d-block">
            <sup>*</sup> Untuk menambahkan gambar pada pertanyaan, silahkan klik icon 'upload'
            lalu pilih gambar (jpg, jpeg, atau png). Setelah itu, klik tombol 'upload'.
        </small>
        <small class="d-block">
            <sup>*</sup> File gambar hanya bisa diisi satu per satu.
        </small>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="table-title m-0"></h5>
                    <div class="question-action">
                        <button type="button" class="btn btn-primary" id="create-question" <?= $disable_btn; ?>>
                            <i class="fas fa-plus mr-1"></i>
                            Create Question
                        </button>
                        <span class="px-1"></span>
                        <button type="button" class="btn btn-danger" id="lock-exam" <?= $disable_btn; ?>>
                            <i class="fas fa-lock mr-1"></i>
                            <?= $disable_btn ? "Exam Locked" : "Lock Exam"; ?>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="questions-table" style="width: 100%;">
                            <thead class="bg-dark" style="color: #f0f5f9;">
                                <tr>
                                    <th scope="col" class="table-col">ID</th>
                                    <th scope="col" class="table-col">Title</th>
                                    <th scope="col" class="table-col">Answer</th>
                                    <th scope="col" class="table-col">Type</th>
                                    <th scope="col" class="table-col">Image</th>
                                    <th scope="col" class="table-col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Question Modal-->
    <div class="modal fade" id="questionModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true">
        <form method="POST" id="question-form">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="questionModalLabel"></h5>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group mb-3 mt-3">
                                <label for="question-title">Question</label>
                                <textarea form="question-form" name="question-title" class="form-control form-control-user" id="question-title" placeholder="Type question here" spellcheck="false" autocomplete="off" rows="5"></textarea>
                                <small class="invalid-question text-danger mb-0"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="option-a">Option A</label>
                                <input type="text" name="option-a" class="form-control form-control-user" id="option-a" placeholder="Option A" spellcheck="false" autocomplete="off">
                                <small class="invalid-option-a text-danger mb-0"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="option-b">Option B</label>
                                <input type="text" name="option-b" class="form-control form-control-user" id="option-b" placeholder="Option B" spellcheck="false" autocomplete="off">
                                <small class="invalid-option-b text-danger mb-0"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="option-c">Option C</label>
                                <input type="text" name="option-c" class="form-control form-control-user" id="option-c" placeholder="Option C" spellcheck="false" autocomplete="off">
                                <small class="invalid-option-c text-danger mb-0"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="option-d">Option D</label>
                                <input type="text" name="option-d" class="form-control form-control-user" id="option-d" placeholder="Option D" spellcheck="false" autocomplete="off">
                                <small class="invalid-option-d text-danger mb-0"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="option-e">Option E</label>
                                <input type="text" name="option-e" class="form-control form-control-user" id="option-e" placeholder="Option E" spellcheck="false" autocomplete="off">
                                <small class="invalid-option-e text-danger mb-0"></small>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="answer">Right Answer</label>
                                    <select class="custom-select" name="answer" id="answer">
                                        <option value="">Select Right Answer</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                        <option value="d">D</option>
                                        <option value="e">E</option>
                                    </select>
                                    <small class="invalid-answer text-danger mb-0"></small>
                                </div>

                                <div class="col-md-6">
                                    <label for="type">Question Type</label>
                                    <select class="custom-select" name="type" id="type">
                                        <option value="">Select Question Type</option>
                                        <option value="Bhs. Indonesia">Bhs. Indonesia</option>
                                        <option value="Bhs. Inggris">Bhs. Inggris</option>
                                        <option value="Matematika">Matematika</option>
                                        <option value="TPA">TPA</option>
                                        <option value="Ilmu Komputer">Ilmu Komputer</option>
                                    </select>
                                    <small class="invalid-type text-danger mb-0"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" id="question-cancel-submit" type="button" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="exam-id" id="exam-id">
                        <input type="hidden" name="question-id" id="question-id">
                        <input type="hidden" name="action" id="action" value="create">
                        <button type="submit" name="question-submit" id="question-submit" class="btn btn-dark"></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>