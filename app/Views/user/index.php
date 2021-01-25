<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<!-- flash data -->
<div class="flash-data" data-flash='<?= session()->getFlashData("message"); ?>' data-title="Edit Success" data-icon="success"></div>
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h2 mb-4 text-gray-700">My Profile</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-left-<?= in_groups('admin') ? 'info' : 'success'; ?> bg-light mb-4 p-3">
                <div class="row no-gutters align-items-center">
                    <div class="col-md-4 my-3 d-flex">
                        <img id="profile-img" class="card-img rounded-circle shadow m-auto p-1" src="<?= base_url('/img/profile/' . user()->profile_pict); ?>" alt="<?= user()->profile_pict; ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light d-flex align-content-center">
                                    <h4 class="card-title d-inline-block"><?= user()->username; ?></h4>
                                    <div>
                                        <span class="ml-2 py-1 badge badge-<?= in_groups('admin') ? 'info' : 'success'; ?>">
                                            <?= in_groups('admin') ? 'Admin' : 'Student'; ?>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-group-item bg-light"><?= user()->email; ?></li>

                                <?php if (user()->fullname) : ?>
                                    <li class="list-group-item bg-light"><?= user()->fullname; ?></li>
                                <?php endif; ?>

                                <?php if (user()->gender) : ?>
                                    <li class="list-group-item bg-light"><?= user()->gender; ?></li>
                                <?php endif; ?>

                                <?php if (user()->phone_number) : ?>
                                    <li class="list-group-item bg-light"><?= user()->phone_number; ?></li>
                                <?php endif; ?>

                                <?php if (user()->address) : ?>
                                    <li class="list-group-item bg-light"><?= user()->address; ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>