<?= $this->extend('templates/index') ?>

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
    #table2 tbody tr.selected {
        background-color: #82B6D9;
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $title; ?></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-bbb card-outline">
                        <div class="card-header ">
                            <div class="row">
                                <div class=" form-group col">
                                    <label class="" for="edit-recipient_company">领用公司</label>
                                    <input type="hidden" class="form-control" id="sto_id" value="<?= $head[0]['sto_id']; ?>">
                                    <input type="hidden" class="form-control" id="updated_at" value="<?= $head[0]['updated_at']; ?>">
                                    <select id="edit-recipient_company" class="select2-edit-recipient_company form-control" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="form-group col" style="padding: 0 0 0 7.5px;">
                                    <label class="" for="edit-recipient_dept">领用部门</label>
                                    <div class="swapselect">
                                        <select class="form-control select2-edit-recipient_dept" style="width: 100%;">
                                        </select>
                                        <div class="invalid-feedback edit-recipient_dept-invalid">
                                        </div>
                                    </div>
                                    <div class="swaptext">
                                        <input type="text" class="form-control text-edit-recipient_dept" placeholder="输入领用部门" maxlength="30">
                                        <div class="invalid-feedback edit-recipient_dept-invalid">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4" style="padding: 0 7.5px 0 0;">
                                    <button type="button" class="btn btn-outline-secondary float-right" style="margin: 8px 0 0;" id="swaps"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-outline-secondary float-right" style="margin: 8px 0 0;" id="swapt"><i class="fas fa-chevron-circle-down" aria-hidden="true"></i></button>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="edit-recipient_name">领用人</label>
                                    <input type="text" class="form-control" id="edit-recipient_name" placeholder="输入领用人" maxlength="40">
                                    <div class="invalid-feedback edit-recipient_name-invalid">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label class="" for="sto_date">出库日期</label>
                                    <input type="date" class="form-control" id="edit-sto_date">
                                    <div class="invalid-feedback sto_date-invalid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="table-edit" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>公司</th>
                                        <th>物料代码</th>
                                        <th>物料名称与规格型号</th>
                                        <th>单位</th>
                                        <th>出库量</th>
                                        <th>库位</th>
                                        <th>备注</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button id="save-edit" class="btn btn-primary float-right">
                                <i class="fa fa-save" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<?= $this->endSection() ?>

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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/stock_out_edit.js"></script>

<?= $this->endSection() ?>