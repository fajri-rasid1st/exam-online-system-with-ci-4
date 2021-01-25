<?= $this->extend('auth/layout/index'); ?>

<?= $this->section('content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Registration Failed" data-icon="error"></div>
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Process Success" data-icon="success"></div>
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
                                <div class="text-left">
                                    <h1 class="h4 text-gray-700 mb-4">Exam | Create Account</h1>
                                </div>

                                <form class="user" action="<?= route_to('register') ?>" method="post">

                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" value="<?= old('username') ?>" id="exampleInputUsername" placeholder="Username" spellcheck="false" autocomplete="off" autofocus>
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.username') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" value="<?= old('email') ?>" id="exampleInputEmail" placeholder="Email Address" spellcheck="false" autocomplete="off">
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3">
                                            <input type="password" name="password" class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" id="exampleInputPassword" placeholder="Password" spellcheck="false" autocomplete="off">
                                            <div class="invalid-feedback pl-3">
                                                <?= session('errors.password') ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" name="pass_confirm" class="form-control form-control-user <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" id="exampleRepeatPassword" placeholder="Repeat Password" spellcheck="false" autocomplete="off">
                                            <div class="invalid-feedback pl-3">
                                                <?= session('errors.pass_confirm') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Create Account
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <p class="small m-0">
                                        Already Have an Account?
                                        <a href="<?= route_to('login') ?>">Sign In!</a>
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