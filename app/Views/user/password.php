<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Process Failed" data-icon="error"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("info"); ?>' data-title="On Process" data-icon="info"></div>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h2 mb-4 text-gray-700">Change Password</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow border-left-secondary bg-light mb-4 p-3">
                <div class="row align-items-center">
                    <div class="col-md">
                        <div class="card-body">
                            <form action="<?= base_url('user/attempt_password'); ?>" method="POST">
                                <?= csrf_field(); ?>
                                <div class="form-group row mb-4 d-flex flex-column">
                                    <div class="col-md mb-3">
                                        <label for="email" class="mb-1">Email Address</label>
                                        <div class="mb-2">
                                            <small class="d-block"><sup>*</sup> We will send you a token in this email.</small>
                                            <small class="d-block"><sup>*</sup> After you receive token, you can use it for change your password.</small>
                                            <small class="d-block"><sup>*</sup> The token will be expired in 1 hour.</small>
                                        </div>
                                        <input type="email" name="email" class="form-control form-control-user" id="email" placeholder="Your email address" spellcheck="false" autocomplete="off">
                                    </div>

                                    <div class="col-md">
                                        <button type="submit" name="token-submit" id="token-submit" class="btn btn-primary">Send Token</button>
                                    </div>
                                </div>
                            </form>

                            <form action="<?= base_url('user/attempt_reset'); ?>" method="POST">
                                <?= csrf_field(); ?>
                                <div class="form-group mb-3">
                                    <label for="confirm-email">Confirm Email Address</label>
                                    <input type="email" name="confirm-email" class="form-control form-control-user <?= $validation->hasError('confirm-email') ? 'is-invalid' : ''; ?>" value="<?= old('confirm-email') ? old('confirm-email') : ''; ?>" id="confirm-email" placeholder="Your email address again" spellcheck="false" autocomplete="off">
                                    <div class="invalid-feedback mb-0">
                                        <?= $validation->getError('confirm-email'); ?>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="token">Token</label>
                                    <input type="text" name="token" class="form-control form-control-user <?= $validation->hasError('token') ? 'is-invalid' : ''; ?>" value="<?= old('token') ? old('token') : ''; ?>" id="token" placeholder="Token for reset password" spellcheck="false" autocomplete="off">
                                    <div class="invalid-feedback mb-0">
                                        <?= $validation->getError('token'); ?>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="new-password">New Password</label>
                                    <input type="password" name="new-password" class="form-control form-control-user <?= $validation->hasError('new-password') ? 'is-invalid' : ''; ?>" id="new-password" placeholder="New password" spellcheck="false" autocomplete="off">
                                    <div class="invalid-feedback mb-0">
                                        <?= $validation->getError('new-password'); ?>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="repeat-password">Repeat New Password</label>
                                    <input type="password" name="repeat-password" class="form-control form-control-user <?= $validation->hasError('repeat-password') ? 'is-invalid' : ''; ?>" id="repeat-password" placeholder="Repeat new password" spellcheck="false" autocomplete="off">
                                    <div class="invalid-feedback mb-0">
                                        <?= $validation->getError('repeat-password'); ?>
                                    </div>
                                </div>

                                <div class="footer border-0">
                                    <div class="d-flex justify-content-end">
                                        <a href="<?= base_url('update_password/' . user()->id); ?>" class="btn btn-secondary mr-2" role="button">Cancel</a>
                                        <input type="hidden" name="id" id="id" value="<?= user()->id; ?>">
                                        <button type="submit" name="reset-submit" id="reset-submit" class="btn btn-dark ml-2">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>