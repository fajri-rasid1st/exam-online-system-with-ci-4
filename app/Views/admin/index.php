<?= $this->extend('layout/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <h1 class="h2 mb-4 text-gray-700">User List</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="table-title m-0">Users Table</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="users-table" style="width: 100%;">
                            <thead class="bg-dark" style="color: #f0f5f9;">
                                <tr>
                                    <th scope="col" class="table-col">ID</th>
                                    <th scope="col" class="table-col">Fullname</th>
                                    <th scope="col" class="table-col">Username</th>
                                    <th scope="col" class="table-col">Email Address</th>
                                    <th scope="col" class="table-col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit User Modal-->
    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <form method="POST" id="edit-user-form">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">User Profile Edit</h5>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group row mb-2 mt-3">
                                <div class="col-md mb-4 mx-auto">
                                    <div class="text-center">
                                        <img class="profile-pict-edit rounded-circle shadow" width="120">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-md-6 mb-2">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control form-control-user" id="username" placeholder="Username" spellcheck="false" autocomplete="off">
                                    <small class="invalid-username text-danger mb-0"></small>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-user" id="email" placeholder="Email Address" spellcheck="false" autocomplete="off">
                                    <small class="invalid-email text-danger mb-0"></small>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-md-4 mb-2">
                                    <label for="fullname">Fullname</label>
                                    <input type="text" name="fullname" class="form-control form-control-user" id="fullname" placeholder="Fullname" spellcheck="false" autocomplete="off">
                                    <small class="invalid-fullname text-danger mb-0"></small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control form-control-user" id="phone_number" placeholder="+62..." spellcheck="false" autocomplete="off">
                                    <small class="invalid-phone_number text-danger mb-0"></small>
                                </div>
                                <div class="col-md-4">
                                    <label for="gender">Gender</label>
                                    <select class="custom-select" name="gender" id="gender">
                                        <option value="">Select your gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <small class="invalid-gender text-danger mb-0"></small>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control form-control-user" id="address" placeholder="Address" spellcheck="false" autocomplete="off">
                                <small class="invalid-address text-danger mb-0"></small>
                            </div>
                            <div class="note mb-2">
                                <small class="text-danger mb-0">
                                    <sup>*</sup>Note : Admin can't change user profile picture.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" id="admin-cancel-submit" type="button" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="hidden-id" id="hidden-id">
                        <input type="hidden" name="edit" id="edit" value="edit">
                        <button type="submit" name="admin-user-submit" id="admin-user-submit" class="btn btn-dark">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>