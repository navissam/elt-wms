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
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">库存</h1>
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
                        <div class="card-body">
                            <div class="col">
                                <div class="form-group float-right">
                                    <span>库存总数量 </span>
                                    <label id="allqty" style="font-size: 20px;"></label>
                                </div>
                            </div>
                            <table id="table1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>序号</th>
                                        <th>公司</th>
                                        <th>物料代码</th>
                                        <th>物料名称与规格编号</th>
                                        <th>单位</th>
                                        <th>数量</th>
                                        <th>库位</th>
                                        <th>安全库存</th>
                                        <th>备注</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- Modal Switch-->
    <div class="modal fade" id="switchModal" tabindex="-1" role="dialog" aria-labelledby="switchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="switchModalLabel"><span class="goods_id"></span> - 库位变更</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="font-weight-bold">序号: <span class="inv_id"></span></td>
                            <td class="font-weight-bold">公司: <span class="company"></span></td>
                        </tr>
                    </table>
                    <div class="form-group row mt-4">
                        <div class="col-2 mt-1">
                            <label>库位</label>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="location" readonly>
                        </div>
                        <div class="col-1">
                            <i class="fas fa-long-arrow-alt-right fa-2x" style="padding: 0 15px;" aria-hidden="true"></i>
                        </div>
                        <div class="col-4" style="padding: 0 0 0 7.5px;">
                            <div class="swapselect">
                                <select class="form-control select2-location-switch" style="width: 100%;">
                                </select>
                                <div class="invalid-feedback location2-invalid">
                                </div>
                            </div>
                            <div class="swaptext">
                                <input type="text" class="form-control text-location" placeholder="输入新库位" maxlength="30">
                                <div class="invalid-feedback location2-invalid">
                                </div>
                            </div>
                        </div>
                        <div class="col-1" style="padding: 0 7.5px 0 0;">
                            <button type="button" class="btn btn-block btn-outline-secondary float-right" id="swaps"><i class="fas fa-plus" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-block btn-outline-secondary float-right" style="margin: 0 0 0;" id="swapt"><i class="fas fa-chevron-circle-down" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-2 mt-1">
                            <label>数量</label>
                        </div>
                        <div class="col-4">
                            <input type="hidden" class="form-control" id="company">
                            <input type="hidden" class="form-control" id="goods_id">
                            <input type="text" class="form-control" id="qty" readonly>
                        </div>
                        <div class="col-1">
                            <i class="fas fa-long-arrow-alt-right fa-2x" style="padding: 0 15px;" aria-hidden="true"></i>
                        </div>
                        <div class="col-5">
                            <input type="number" class="form-control" id="qty2" min="0" maxlength="2147483647">
                            <div class="invalid-feedback qty2-invalid">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-2 mt-1">
                            <label for="remark">备注</label>
                        </div>
                        <div class="col-10">
                            <input type="text" class="form-control" id="remark" placeholder="输入备注" maxlength="255">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                    <button type="button" class="btn btn-primary" id="save-switch"><i class="fas fa-save"></i> 保存</button>
                </div>
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/inventory_index.js"></script>
<?= $this->endSection() ?>