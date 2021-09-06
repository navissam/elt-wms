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
            cursor: default;
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
            cursor: default;
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
            cursor: default;
        }
    }

    @media (min-width: 1600px) {
        h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 40px 0 10px;
            cursor: default;
        }
    }
</style>
<?= $this->endSection() ?>
<?php
$db      = \Config\Database::connect();

$roleID = session()->get('roleID') ?? 0;
if ($roleID != 0) {
    $rp = $db->table('role_permission');
    $rp->select('role_permission.permID');
    $rp->join('permission', 'permission.permID = role_permission.permID', 'inner');
    $rp->where('permission.deleted_at', null);
    $rp->where('permission.status', 1);
    $rp->where('roleID', $roleID);
    $rp_ = $rp->get()->getResultArray();
    $permIDs = [];
    foreach ($rp_ as $value) {
        array_push($permIDs, intval($value['permID']));
    }
    // dd($permIDs);
}
?>
<?= $this->section('content') ?>
<div class="content-wrapper bg">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2" style="padding: 0;">
                <div class="col-lg-6">
                    <h1 class="m-0 welcome">欢迎，<?= session()->get('name') ?></h1>
                </div><!-- /.col -->
            </div>
            <div class="row mb-2">
                <div class="col-lg-1" style="padding: 0 7.5px 0 7.5px;">
                    <?php
                    if (in_array(20, $permIDs)) :
                    ?>
                        <div class="small-box bg-info mt-2" id="inv">
                            <div class="icon">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <div class="inner">
                                <h2>库存</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(22, $permIDs)) :
                    ?>
                        <div class="small-box bg-success mt-2" id="sto">
                            <div class="icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="inner">
                                <h2>出库</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(24, $permIDs)) :
                    ?>
                        <div class="small-box bg-danger mt-2" id="scr">
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="inner">
                                <h2>报废</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="col-lg-1">
                    <?php
                    if (in_array(21, $permIDs)) :
                    ?>
                        <div class="small-box bg-primary mt-2" id="sti">
                            <div class="icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="inner">
                                <h2>入库</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(23, $permIDs)) :
                    ?>
                        <div class="small-box bg-teal mt-2" id="ret">
                            <div class="icon">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <div class="inner">
                                <h2>退库</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(26, $permIDs)) :
                    ?>
                        <div class="small-box bg-secondary mt-2" id="report">
                            <div class="icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="inner">
                                <h2>报表</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="col-lg-2">
                    <?php
                    if (in_array(28, $permIDs)) :
                    ?>
                        <div class="small-box bg-lightblue mt-2" id="swc">
                            <div class="icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="inner">
                                <h2>库位变更</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(19, $permIDs)) :
                    ?>
                        <div class="small-box bg-maroon mt-2" id="history">
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="inner">
                                <h2>物料库存历史</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(19, $permIDs) || in_array(25, $permIDs) || in_array(29, $permIDs)) :
                    ?>
                        <div class="small-box bg-olive mt-2" id="basic">
                            <div class="icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="inner">
                                <h2>基础管理</h2>
                            </div>
                        </div>
                </div>
                <div class="col-lg-2" style="padding: 0 7.5px 0 7.5px;">
                <?php
                    endif;
                    if (in_array(27, $permIDs)) :
                ?>
                    <div class="small-box bg-info mt-2" id="print">
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="inner">
                            <h2>单据交接</h2>
                        </div>
                    </div>
                    <div class="small-box bg-teal mt-2" id="claim">
                        <div class="icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="inner">
                            <h2>领用单打印</h2>
                        </div>
                    </div>
                <?php
                    endif;
                ?>
                </div>
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
    </div>
    <!-- reportModal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="small-box bg-primary" id="sti_report">
                                <div class="icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="inner">
                                    <h2>入库报表</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="small-box bg-success" id="sto_report">
                                <div class="icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="inner">
                                    <h2>出库报表</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="small-box bg-teal" id="ret_report">
                                <div class="icon">
                                    <i class="fas fa-undo-alt"></i>
                                </div>
                                <div class="inner">
                                    <h2>退库报表</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="small-box bg-danger" id="scr_report">
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="inner">
                                    <h2>报废报表</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="small-box bg-info" id="swc_report">
                        <div class="icon">
                            <i class="fas fa-random"></i>
                        </div>
                        <div class="inner">
                            <h2>库位变更报表</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- basicModal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <?php
                    if (in_array(25, $permIDs)) :
                    ?>
                        <div class="small-box bg-info mt-3" id="company">
                            <div class="icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="inner">
                                <h2>公司管理</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(29, $permIDs)) :
                    ?>
                        <div class="small-box bg-teal mt-3" id="dept">
                            <div class="icon">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div class="inner">
                                <h2>部门管理</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    if (in_array(19, $permIDs)) :
                    ?>
                        <div class="small-box bg-olive mt-3" id="goods">
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="inner">
                                <h2>物料管理</h2>
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
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