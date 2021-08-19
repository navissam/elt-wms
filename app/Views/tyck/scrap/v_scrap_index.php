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
                    <div class="card card-bbb card-outline">
                        <div class="card-header">
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                                <div class="form-group col">
                                    <label class="" for="scr_date">报废日期</label>
                                    <input type="date" class="form-control" id="scr_date">
                                    <div class="invalid-feedback scr_date-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="reason">物料报废原因</label>
                                    <input type="text" class="form-control" id="reason" placeholder="输入物料报废原因" maxlength="255">
                                    <div class="invalid-feedback reason-invalid">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-8">
                                    <label for="goods_id">物料代码</label>
                                    <!-- <select id="goods_id" class="select2-goods_id form-control" style="width: 100%;">
                                    </select> -->
                                    <input type="text" class="form-control" id="goods_id" readonly>
                                </div>
                                <div class="col-2">
                                    <label class="" for="stock">库存数量</label>
                                    <input type="number" class="form-control" id="stock" readonly>
                                </div>
                                <div class="col-2 mt-auto">
                                    <button id="add" class="btn btn-secondary btn-block">
                                        <i class="fa fa-search" aria-hidden="true"></i> 寻找物料
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4">
                                    <label class="" for="company">公司</label>
                                    <!-- <select id="company" class="select2-company form-control" style="width: 100%;">
                                    </select> -->
                                    <input type="text" class="form-control" id="company" readonly>
                                </div>
                                <div class="col-5">
                                    <label for="location">库位</label>
                                    <!-- <select id="location" class="form-control select2-location" style="width: 100%;">
                                    </select> -->
                                    <input type="text" class="form-control" id="location" readonly>
                                </div>
                                <div class="col-3">
                                    <label class="" for="qty">报废量</label>
                                    <input type="number" class="form-control" id="qty" placeholder="输入报废量" min="0" maxlength="2147483647">
                                    <div class="invalid-feedback qty-invalid">
                                    </div>
                                </div>
                            </div>
                            <div class="row row-cols-lg-2 row-cols-md-2 row-cols-sm-1">
                                <div class="form-group col">
                                    <label class="" for="applyPIC">申请人</label>
                                    <input type="text" class="form-control" id="applyPIC" placeholder="输入申请人" maxlength="30">
                                    <div class="invalid-feedback applyPIC-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="verifyPIC">审核人</label>
                                    <input type="text" class="form-control" id="verifyPIC" placeholder="输入审核人" maxlength="30">
                                    <div class="invalid-feedback verifyPIC-invalid">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="" for="remark">备注</label>
                                <input type="text" class="form-control" id="remark" placeholder="输入备注" maxlength="255">
                                <div class="invalid-feedback remark-invalid">
                                </div>
                            </div>
                            <hr class="mt-4">
                            <button type="button" class="btn btn-primary mb-2 float-right" id="save"><i class="fas fa-save"></i> 保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">插入物料</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="row mb-5">
                            <div class="form-group col">
                                <label for="qty">数量</label>
                                <input type="number" class="form-control" min="0" name="" id="qty" value="0">
                                <div class="invalid-feedback qty-invalid">
                                </div>
                            </div>
                        </div> -->
                        <table id="table1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>公司</th>
                                    <th>库位</th>
                                    <th>物料代码</th>
                                    <th>物料名称与规格型号</th>
                                    <th>单位</th>
                                    <th>数量</th>
                                    <th>更新时间</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                        <button type="button" class="btn btn-primary" id="insert"><i class="fas fa-plus"></i> 插入</button>
                    </div>
                </div>
            </div>
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/scrap_index.js"></script>

<!-- <script>

</script> -->
<?php $this->endSection(); ?>