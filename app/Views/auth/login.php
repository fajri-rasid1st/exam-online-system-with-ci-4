<?= $this->extend('auth/layout/index'); ?>

<?= $this->section('content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Login Failed" data-icon="error"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Process Success" data-icon="success"></div>
<div class="container my-5">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-md">
                            <div class="p-4">
                                <div class="text-left">
                                    <h1 class="h4 text-gray-700 mb-4">Exam | Login</h1>
                                </div>

                                <form class="user" action="<?= route_to('login') ?>" method="post">

                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="text" name="login" class="form-control form-control-user <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" id="exampleInputUser" placeholder="Username or Email Address" spellcheck="false" autocomplete="off" autofocus>
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.login') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" id="exampleInputPassword" placeholder="Password" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.password') ?>
                                        </div>
                                    </div>

                                    <div class="form-group d-flex justify-content-between">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" name="remember" class="custom-control-input" id="customCheck" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                            <label class="custom-control-label" for="customCheck">
                                                Remember Me
                                            </label>
                                        </div>
                                        <div class="text-right">
                                            <a class="small" href="<?= route_to('forgot') ?>">
                                                Forgot Password?
                                            </a>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <p class="small m-0">
                                        Don't Have an Account?
                                        <a href="<?= route_to('register') ?>">Sign Up!</a>
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