<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <!-- breadcrumb nav -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">User List</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Profile Detail</li>
        </ol>
    </nav>
    <h1 class="h2 mb-4 text-gray-700">User Profile Detail</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow bg-light mb-4 p-2 border-left-<?= $user->role == 'admin' ? 'info' : 'success'; ?>">
                <div class="row no-gutters align-items-center">
                    <div class="col-md-4 my-3 d-flex">
                        <img id="profile-img" class="card-img rounded-circle shadow m-auto p-1" src="<?= base_url('/img/profile/' . $user->profile_pict); ?>" data-action="zoom">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-light d-flex align-content-center">
                                    <h4 class="card-title d-inline-block"><?= $user->username; ?></h4>
                                    <div>
                                        <span class="ml-3 py-1 badge badge-<?= $user->role == 'admin' ? 'info' : 'success'; ?>">
                                            <?= $user->role == 'admin' ? 'Admin' : 'Student'; ?>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-group-item bg-light"><?= $user->email; ?></li>

                                <?php if ($user->fullname) : ?>
                                    <li class="list-group-item bg-light"><?= $user->fullname; ?></li>
                                <?php endif; ?>

                                <?php if ($user->gender) : ?>
                                    <li class="list-group-item bg-light"><?= $user->gender; ?></li>
                                <?php endif; ?>

                                <?php if ($user->phone_number) : ?>
                                    <li class="list-group-item bg-light"><?= $user->phone_number; ?></li>
                                <?php endif; ?>

                                <?php if ($user->address) : ?>
                                    <li class="list-group-item bg-light"><?= $user->address; ?></li>
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