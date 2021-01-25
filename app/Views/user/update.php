<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h2 mb-4 text-gray-700">Edit Profile</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-left-secondary bg-light mb-4 p-3">
                <form action="<?= base_url('user/attempt_update'); ?>" method="POST" enctype="multipart/form-data">

                    <?= csrf_field(); ?>

                    <div class="row no-gutters align-items-center">
                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="form-upload">
                                <div class="preview text-center mb-2">
                                    <img src="<?= base_url('/img/profile/' . $user["profile_pict"]); ?>" id="preview-img" class="card-img rounded-circle shadow m-auto p-1" alt="<?= $user["profile_pict"]; ?>">
                                </div>

                                <div class="text-success mb-2">
                                    <small><sup>*</sup>recommended image ratio 1:1 (square)</small>
                                </div>

                                <input type="file" id="profile_pict" name="profile_pict" class="<?= $validation->hasError('profile_pict') ? 'is-invalid' : ''; ?>" onchange="showPreview();">

                                <label for="profile_pict" class="btn btn-default <?= $validation->hasError('profile_pict') ? 'is-invalid' : ''; ?>">
                                    <i class="fas fa-cloud-upload-alt mr-1"></i>
                                    <span class="label-text">Choose file</span>
                                </label>

                                <div class="invalid-feedback text-center m-0">
                                    <?= $validation->getError('profile_pict'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="form-group row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control form-control-user <?= $validation->hasError('username') ? 'is-invalid' : ''; ?>" value="<?= old('username') ? old('username') : $user["username"]; ?>" id="username" placeholder="Username" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback">
                                            <?= $validation->getError("username"); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">Email Address</label>
                                        <input type="email" name="email" class="form-control form-control-user <?= $validation->hasError('email') ? 'is-invalid' : ''; ?>" value="<?= old('email') ? old('email') : $user["email"]; ?>" id="email" placeholder="Email Address" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('email'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <div class="col-md-4 mb-2">
                                        <label for="fullname">Fullname</label>
                                        <input type="text" name="fullname" class="form-control form-control-user <?= $validation->hasError('fullname') ? 'is-invalid' : ''; ?>" value="<?= old('fullname') ? old('fullname') : $user["fullname"]; ?>" id="fullname" placeholder="Fullname" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('fullname'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" class="form-control form-control-user <?= $validation->hasError('phone_number') ? 'is-invalid' : ''; ?>" value="<?= old('phone_number') ? old('phone_number') : $user["phone_number"]; ?>" id="phone_number" placeholder="+62....." spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('phone_number'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="gender">Gender</label>
                                        <select class="custom-select <?= $validation->hasError('gender') ? 'is-invalid' : ''; ?>" name="gender" id="gender">
                                            <option value="">Select your gender</option>
                                            <option <?= old('gender') ? (old('gender') == 'Male' ? 'selected' : '') : ($user['gender'] == 'Male' ? 'selected' : ''); ?> value="Male">Male</option>
                                            <option <?= old('gender') ? (old('gender') == 'Female' ? 'selected' : '') : ($user['gender'] == 'Female' ? 'selected' : ''); ?> value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('gender'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" class="form-control form-control-user <?= $validation->hasError('address') ? 'is-invalid' : ''; ?>" value="<?= old('address') ? old('address') : $user["address"]; ?>" id="address" placeholder="Address" spellcheck="false" autocomplete="off">
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('address'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0">
                                <div class="d-flex justify-content-end">
                                    <a href="<?= base_url('user/' . $user["id"]); ?>" class="btn btn-secondary mr-2" role="button">Cancel</a>
                                    <input type="hidden" name="id" id="id" value="<?= $user["id"]; ?>">
                                    <input type="hidden" name="oldpict" id="oldpict" value="<?= $user["profile_pict"]; ?>">
                                    <button type="submit" name="user-submit" id="user-submit" class="btn btn-dark ml-2">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>