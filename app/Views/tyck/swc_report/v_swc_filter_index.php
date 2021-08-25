<?= $this->extend('templates/index'); ?>

<?= $this->section('style') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Sweetalert2 -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<style>
    #table1 tbody tr.selected {
        background-color: #82B6D9;
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title; ?></h1>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <form action="<?= base_url('tyck/report/swc_report_adv'); ?>" target="_blank" method="post" id="filterForm">
                        <div class="card card-bbb card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <ul class="list-group">
                                            <li class="list-group-item bg-secondary">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <i class="fas fa-eye" aria-hidden="true"></i> 显示内容
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input custom-control-input-danger" type="checkbox" id="chooseAll">
                                                            <label for="chooseAll" class="custom-control-label float-right">全选</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="text-danger" id="alert">
                                                    必须选择要显示的内容
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="swc_date" value="swc_date">
                                                    <label for="swc_date" class="custom-control-label">变更日期</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="company" value="company">
                                                    <label for="company" class="custom-control-label">公司</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="goods_id" value="goods_id">
                                                    <label for="goods_id" class="custom-control-label">物料代码</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="name_type" value="name_type">
                                                    <label for="name_type" class="custom-control-label">物料名称与规格编号</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="unit" value="unit">
                                                    <label for="unit" class="custom-control-label">单位</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="qty" value="qty">
                                                    <label for="qty" class="custom-control-label">数量</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="from_location" value="from_location">
                                                    <label for="from_location" class="custom-control-label">原库位</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="to_location" value="to_location">
                                                    <label for="to_location" class="custom-control-label">现库位</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="old_stock" value="old_stock">
                                                    <label for="old_stock" class="custom-control-label">原库位数量</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="new_stock" value="new_stock">
                                                    <label for="new_stock" class="custom-control-label">现库位数量</label>
                                                </div>
                                                <div class="custom-control custom-checkbox my-1">
                                                    <input class="custom-control-input choose" type="checkbox" name="choosen[]" id="remark" value="remark">
                                                    <label for="remark" class="custom-control-label">备注</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-8">
                                        <ul class="list-group">
                                            <li class="list-group-item bg-secondary">
                                                <i class="fas fa-filter" aria-hidden="true"></i> 筛选
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="start">开始日期</label>
                                                        <input type="date" class="form-control" name="start" id="start" value="<?= date('Y-m-d'); ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="finish">结束日期</label>
                                                        <input type="date" class="form-control" name="finish" id="finish" value="<?= date('Y-m-d'); ?>">
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <label for="s_company">公司</label>
                                                    <div class="select2-primary">
                                                        <select class="select2-company" name="s_company[]" id="s_company" multiple="multiple" data-placeholder="公司筛选（放空等于全选）" data-dropdown-css-class="select2-primary" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <label for="s_goods_id">物料代码</label>
                                                    <div class="select2-primary">
                                                        <select class="select2-goods_id" name="s_goods_id[]" id="s_goods_id" multiple="multiple" data-placeholder="物料筛选（放空等于全选）" data-dropdown-css-class="select2-primary" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <label for="s_old_location">原库位</label>
                                                    <div class="select2-primary">
                                                        <select class="select2-old_location" name="s_old_location[]" id="s_old_location" multiple="multiple" data-placeholder="原库位筛选（放空等于全选）" data-dropdown-css-class="select2-primary" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="my-2">
                                                    <label for="s_new_location">现库位</label>
                                                    <div class="select2-primary">
                                                        <select class="select2-new_location" name="s_new_location[]" id="s_new_location" multiple="multiple" data-placeholder="现库位筛选（放空等于全选）" data-dropdown-css-class="select2-primary" style="width: 100%;">
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <button type="button" class="btn btn-primary float-right" id="advfilter"><i class="fas fa-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.card-body -->
<?php $this->endSection(); ?>

<?= $this->section('script') ?>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('/'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url('/'); ?>/plugins/select2/js/select2.full.min.js"></script>
<!-- Sweetalert2 -->
<script src="<?= base_url('/'); ?>/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/swc_report_index.js"></script>
<?php $this->endSection(); ?>