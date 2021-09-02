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
                    <div class="card card-bbb card-outline">
                        <div class="card-header">
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                                <div class="form-group col">
                                    <label class="" for="ret_company">退库公司</label>
                                    <select id="ret_company" class="select2-ret_company form-control" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="ret_dept">退库部门</label>
                                    <select id="ret_dept" class="select2-ret_dept form-control" style="width: 100%;">
                                    </select>
                                    <div class="invalid-feedback ret_dept-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="ret_name">退库人</label>
                                    <input type="text" class="form-control" id="ret_name" placeholder="输入退库人" maxlength="30">
                                    <div class="invalid-feedback ret_name-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="ret_date">退库日期</label>
                                    <input type="date" class="form-control" id="ret_date">
                                    <div class="invalid-feedback ret_date-invalid">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="goods_id">物料代码</label>
                                <select id="goods_id" class="select2-goods_id form-control" style="width: 100%;">
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label class="" for="company">公司</label>
                                    <select id="company" class="select2-company form-control" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-5" style="padding: 0 0 0 7.5px;">
                                    <label for="ret_location">库位</label>
                                    <div class="swapselect">
                                        <select class="form-control select2-ret_location" style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="swaptext">
                                        <input type="text" class="form-control text-location" placeholder="输入新库位" maxlength="30">
                                        <div class="invalid-feedback ret_location-invalid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 mt-auto" style="padding: 0 7.5px 0 0;">
                                    <button type="button" class="btn btn-block btn-outline-secondary" style="margin: 8px 0 0;" id="swaps"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-block btn-outline-secondary" id="swapt"><i class="fas fa-chevron-circle-down" aria-hidden="true"></i></button>
                                    <!-- <a class="btn btn-block btn-outline-secondary" id="swaps"><i class="fas fa-plus" aria-hidden="true"></i></a> -->
                                </div>
                            </div>
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                                <div class="form-group col">
                                    <label class="" for="qty">退库量</label>
                                    <input type="number" class="form-control" id="qty" placeholder="输入退库量" min="0" maxlength="2147483647">
                                    <div class="invalid-feedback qty-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="remark">备注</label>
                                    <input type="text" class="form-control" id="remark" placeholder="输入备注" maxlength="255">
                                    <div class="invalid-feedback remark-invalid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <button type="button" class="btn btn-primary float-right" id="save"><i class="fas fa-save"></i> 保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/return_index.js"></script>
<?php $this->endSection(); ?>