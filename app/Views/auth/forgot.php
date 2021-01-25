<?= $this->extend('auth/layout/index'); ?>

<?= $this->section('content'); ?>
<div class="flash-data" data-flash='<?= session()->getFlashData("error"); ?>' data-title="Process Failed" data-icon="error"></div>
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
                                <div class="text-center">
                                    <h1 class="h4 text-gray-700 mb-2">Forgot Your Password?</h1>
                                    <p class="mb-4">
                                        We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!
                                    </p>
                                </div>

                                <form class="user" action="<?= route_to('forgot') ?>" method="post">

                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." spellcheck="false" autocomplete="off" autofocus>
                                        <div class="invalid-feedback pl-3">
                                            <?= session('errors.email') ?>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small" href="<?= route_to('register'); ?>">
                                        Create an Account!
                                    </a>
                                    <p class="small m-0">
                                        Already Have an Account?
                                        <a href="<?= route_to('login'); ?>">Sign In!</a>
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