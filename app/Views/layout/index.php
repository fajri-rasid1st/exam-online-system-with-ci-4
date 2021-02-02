<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title -->
    <title>Exam Browser | <?= $title; ?></title>
    <!-- Custom fonts for this template -->
    <link href="<?= base_url(); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?= base_url(); ?>/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- DateTimePicker -->
    <link rel="stylesheet" href="<?= base_url(); ?>/css/jquery.datetimepicker.min.css">
    <!-- TimeCircle -->
    <link rel="stylesheet" href="<?= base_url(); ?>/css/TimeCircles.css">
    <!-- Tap To Zoom Image -->
    <link rel="stylesheet" href="<?= base_url(); ?>/css/zoom.css">
    <!-- External CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>/css/main.css">
</head>

<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?= $this->include('layout/sidebar'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?= $this->include('layout/topbar'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?= $this->renderSection('page-content'); ?>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white footer-page">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; fajri-rasid1st <?= date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Are you sure you want to log out?</h5>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-dark" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url(); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>/js/sb-admin-2.min.js"></script>
    <script src="<?= base_url(); ?>/js/zoom.js"></script>
    <script src="<?= base_url(); ?>/js/TimeCircles.js"></script>
    <script src="<?= base_url(); ?>/js/sweetalert2.all.min.js"></script>
    <script src="<?= base_url(); ?>/js/jquery.datetimepicker.full.min.js"></script>
    <script src="<?= base_url(); ?>/js/user_management.js"></script>
    <script src="<?= base_url(); ?>/js/exam_management.js"></script>
    <script src="<?= base_url(); ?>/js/question_management.js"></script>
    <script src="<?= base_url(); ?>/js/utilities.js"></script>
    <script src="<?= base_url(); ?>/js/script.js"></script>
    <script>
        const code = "<?= isset($_GET['code']) ? $_GET['code'] : null ?>";
        const page = "<?= isset($_GET['page']) ? $_GET['page'] : null ?>";
    </script>
</body>

</html>