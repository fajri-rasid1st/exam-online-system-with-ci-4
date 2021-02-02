<ul class="navbar-nav bg-dark sidebar sidebar-dark accordion toggled" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url(); ?>">
        <div class="sidebar-brand-icon">
            <i class="fab fa-codepen rotate-15"></i>
        </div>
        <div class="sidebar-brand-text ml-3">Examination</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Brand -->
    <li class="nav-item">
        <a class="nav-link pt-0" href="<?= base_url(); ?>">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        User Profile
    </div>
    <!-- Nav Item - User Management -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true" aria-controls="collapseUser">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar">
            <div class="bg-secondary py-2 collapse-inner rounded">
                <h5 class="collapse-header text-white-50">Profile setting</h5>

                <a class="nav-link pb-0" href="<?= base_url('user'); ?>">
                    <i class="fas fa-user-tag"></i>
                    <span>My Profile</span>
                </a>

                <a class="nav-link pb-0" href="<?= base_url('user/' . user()->id); ?>">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>

                <a class="nav-link" href="<?= base_url('update_password/' . user()->id); ?>">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                </a>
            </div>
        </div>
    </li>

    <?php if (in_groups('admin')) : ?>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            Manage User
        </div>
        <!-- Nav Item - User Settings -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('admin'); ?>">
                <i class="fas fa-users"></i>
                <span>User List</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            Manage Exam
        </div>
        <!-- Nav Item - Examinations -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('exam'); ?>">
                <i class="fas fa-fw fa-table"></i>
                <span>Exam List</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (in_groups('user')) : ?>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
            Manage Exam
        </div>
        <!-- Nav Item - Examinations -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('exam_list'); ?>">
                <i class="fas fa-fw fa-table"></i>
                <span>Exam List</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link pt-0" href="<?= base_url('logout'); ?>">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>