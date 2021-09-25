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
                    <form action="<?= base_url('tyck/request/req_print_array'); ?>" target="_blank" method="post" id="printReq">
                        <div class="card card-bbb card-outline">
                            <div class="card-header ">
                                <div class="row">
                                    <!-- <div class="row row-cols-lg-4 row-cols-md-2 row-cols-sm-1"> -->
                                    <div class="form-group col">
                                        <label class="" for="req_company">领用公司</label>
                                        <select id="req_company" name="req_company" class="select2-req_company form-control" style="width: 100%;">
                                        </select>
                                    </div>
                                    <div class="form-group col" style="padding: 0 0 0 7.5px;">
                                        <!-- <label class="" for="req_dept">领用部门</label>
                                        <select id="req_dept" name="req_dept" class="select2-req_dept form-control" style="width: 100%;">
                                        </select>
                                        <div class="invalid-feedback req_dept-invalid">
                                        </div>
                                    </div>
                                <div class="col-5" style="padding: 0 0 0 7.5px;"> -->
                                        <label for="req_dept">领用部门</label>
                                        <div class="swapselect">
                                            <select class="form-control select2-req_dept" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="swaptext">
                                            <input type="text" class="form-control text-req_dept" placeholder="输入领用部门" maxlength="255">
                                        </div>
                                    </div>
                                    <div class="form-group mt-4" style="padding: 0 7.5px 0 0;">
                                        <button type="button" class="btn btn-outline-secondary" style="margin: 8px 0 0;" id="swaps"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                        <button type="button" class="btn btn-outline-secondary" style="margin: 8px 0 0;" id="swapt"><i class="fas fa-chevron-circle-down" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="form-group col">
                                        <label class="" for="req_name">领用人</label>
                                        <input type="text" class="form-control" id="req_name" name="req_name" placeholder="输入领用人" maxlength="40">
                                    </div>
                                    <div class="form-group col">
                                        <label class="" for="req_date">领用日期</label>
                                        <input type="date" class="form-control" id="req_date" name="req_date">
                                        <div class="invalid-feedback req_date-invalid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <button type="button" id="add" class="btn btn-success">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" id="print" class="btn btn-primary float-right">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <table id="table1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>公司</th>
                                            <th>物料代码</th>
                                            <th>物料名称与规格型号</th>
                                            <th>单位</th>
                                            <th>数量</th>
                                            <th>库位</th>
                                            <th>用途</th>
                                            <th>备注</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    <!-- /.content -->
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
                <div class="row">
                    <div class="form-group col">
                        <label for="qty">数量</label>
                        <input type="number" class="form-control choose" min="0" name="" id="qty" value="0">
                        <div class="invalid-feedback qty-invalid">
                        </div>
                    </div>
                    <div class="form-group col">
                        <label for="useFor">用途</label>
                        <input type="text" class="form-control choose" placeholder="输入用途" id="useFor" maxlength="255">
                    </div>
                    <div class="form-group col">
                        <label for="remark">备注</label>
                        <input type="text" class="form-control choose" placeholder="输入备注" id="remark" maxlength="255">
                    </div>
                </div>
                <table id="table2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/request_index.js"></script>

<?= $this->endSection() ?>