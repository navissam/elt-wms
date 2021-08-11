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

<body class="">

    <section class="content">
        <div class="error-page">
            <h2 class="headline text-bbb"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-bbb"></i> 错误</h3>

                <p>
                    页面找不到，请回到<a href="<?= base_url() ?>">主页</a>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</body>

</html>