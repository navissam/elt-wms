<?= $this->extend('templates/index') ?>



<?= $this->section('content') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-teal"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-teal"></i> 错误</h3>

                <p>
                    页面找不到，请回到<a href="<?= base_url() ?>">主页</a>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
<?= $this->endSection() ?>