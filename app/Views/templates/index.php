<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>仓储管理系统</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('/'); ?>/fonts/font.css">
    <?= $this->renderSection('style') ?>
    <style>
        .navbar-bbb {
            background-color: #1B87DE;
        }

        .sidebar-dark-bbb .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-light-bbb .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #1B87DE;
            color: #fff;
        }

        .bg-bbb {
            background-color: #1B87DE;
            color: #fff;
        }

        .card-bbb.card-outline {
            border-top: 3px solid #1B87DE;
        }

        .text-bbb {
            color: #1B87DE;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="<?= base_url() ?>/dist/img/logo.png" alt="cangku" height="120" width="120">
                <p class="h3 mt-5 text-bbb animation__shake">仓储管理系统</p>
            </div> -->
        <!-- Navbar -->
        <?= $this->include('templates/navbar') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('templates/sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <?= $this->renderSection('content') ?>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?= $this->include('templates/footer') ?>

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
    <!-- Moment.js -->
    <script src="<?= base_url(); ?>/plugins/moment/moment.min.js"></script>

    <?= $this->renderSection('script') ?>

</body>

</html>