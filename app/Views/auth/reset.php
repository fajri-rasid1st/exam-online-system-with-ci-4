<?= $this->extend('auth/layout/index'); ?>

<?= $this->section('content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("info"); ?>' data-title="On Process" data-icon="info"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Proccess Failed" data-icon="error"></div>
<div class="container my-5">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-md">
                            <div class="p-4">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-700 mb-2">Reset Your Password</h1>
                                    <p class="mb-4">
                                        Enter the token you received via email, your email address, and your new password.
                                    </p>
                                </div>

                                <form class="user" action="<?= route_to('reset-password') ?>" method="post">

                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="text" name="token" class="form-control form-control-user <?php if (session('errors.token')) : ?>is-invalid<?php endif ?>" value="<?= old('token', $token ?? '') ?>" id="exampleInputToken" placeholder="Token" spellcheck="false" autocomplete="off" autofocus>
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.token') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" value="<?= old('email') ?>" id="exampleInputEmail" placeholder="Email Address" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" name="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" id="exampleInputPassword" placeholder="New Password" spellcheck="false" autocomplete="off">
                                            <div class="invalid-feedback pl-3">
                                                <?= session('errors.password') ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" name="pass_confirm" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" id="exampleRepeatPassword" placeholder="Repeat New Password" spellcheck="false" autocomplete="off">
                                            <div class="invalid-feedback pl-3">
                                                <?= session('errors.pass_confirm') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <p class="small m-0">
                                        Don't Have an Account?
                                        <a href="<?= route_to('register'); ?>">Sign Up!</a>
                                    </p>
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