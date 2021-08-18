<?= $this->extend('templates/index') ?>

<?= $this->section('style') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<style>
    .bg {
        background: url("<?= base_url(''); ?>/img/home.png") no-repeat center center fixed;
        background-size: cover;
    }

    .welcome {
        color: white;
    }

    @media (max-width: 1300px) {
        h2 {
            font-size: 1rem;
            font-weight: 700;
            margin: 10px 0 10px;
            text-align: center;
        }

        .icon {
            display: none;
        }
    }

    @media (min-width: 1300px) and (max-width: 1400px) {
        h2 {
            font-size: 1.37rem;
            font-weight: 700;
            margin: 10px 0 10px;
            text-align: center;
        }

        .icon {
            display: none;
        }
    }

    @media (min-width: 1400px) and (max-width: 1600px) {
        h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 40px 0 10px;
        }
    }

    @media (min-width: 1600px) {
        h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 40px 0 10px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper bg">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6">
                    <h1 class="m-0 welcome">欢迎，<?= session()->get('name') ?></h1>
                </div><!-- /.col -->
            </div>
            <div class="row mb-2">
                <div class="col-lg-1">
                    <div class="small-box bg-info mt-2" id="inv">
                        <div class="icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="inner">
                            <h2>库存</h2>
                        </div>
                    </div>
                    <div class="small-box bg-teal" id="ret">
                        <div class="icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="inner">
                            <h2>退库</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="small-box bg-primary mt-2" id="sti">
                        <div class="icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="inner">
                            <h2>入库</h2>
                        </div>
                    </div>
                    <div class="small-box bg-danger" id="scr">
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="inner">
                            <h2>报废</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="small-box bg-success mt-2" id="sto">
                        <div class="icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="inner">
                            <h2>出库</h2>
                        </div>
                    </div>
                    <div class="small-box bg-secondary" id="report">
                        <div class="icon">
                            <i class="fas fa-clipboard-check"></i>
                            <!-- <i class="fas fa-file-export"></i> -->
                        </div>
                        <div class="inner">
                            <h2>报表</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="small-box bg-lightblue mt-2" id="history">
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="inner">
                            <h2>物料库存历史</h2>
                        </div>
                    </div>
                    <div class="small-box bg-olive" id="goods">
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="inner">
                            <h2>物料管理</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <!-- safety stock -->
                    <?php if ($x) : ?>
                        <div class="card mt-2" id="safety">
                            <!-- <div class="card-header">
                            <h2>安全库存</h2>
                        </div> -->
                            <!-- /.card-header -->

                            <div class="card-body">
                                <table id="table1" class="table table-sm table-bordered">
                                    <h5><strong>安全库存</strong></h5>
                                    <thead>
                                        <tr>
                                            <th>公司</th>
                                            <th>物料代码</th>
                                            <th>物料名称与规格型号</th>
                                            <th>单位</th>
                                            <th>数量</th>
                                            <th>库位</th>
                                            <!-- <th>状态</th> -->
                                        </tr>
                                    </thead>
                                    <tbody class="bg-danger">
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card -->
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
                <!-- /.col -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('/'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/dashboard_index.js"></script>
<?= $this->endSection() ?>