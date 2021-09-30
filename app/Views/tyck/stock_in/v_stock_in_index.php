<?= $this->extend('templates/index'); ?>

<?= $this->section('style') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                    <h1 class="m-0">入库</h1>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Default box -->
                    <?php if (!($isset_temp)) : ?>
                        <div class="card card-bbb card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="alert alert-info alert-dismissible">
                                            <h5><i class="icon fas fa-info"></i> 未有导入!</h5>
                                            至今为止未有还挂着的入库单
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-primary mb-1" href="<?= base_url(); ?>/doc/通用仓库-入库导入模板.xlsx">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            下载模板
                                        </a>
                                        <form method="POST" id="myForm" enctype="multipart/form-data">
                                            <div class="custom-file mb-1">
                                                <input type="file" name="file" class="custom-file-input" id="file">
                                                <label class="custom-file-label" for="file">选择文件</label>
                                                <div class="invalid-feedback file-invalid">
                                                </div>
                                            </div>
                                            <button type="button" id="upload" class="btn btn-primary float-right ">
                                                <i class="fa fa-upload" aria-hidden="true"></i>
                                                上传
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4 mt-auto">
                                        <div class="loading-upload">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <span class="h5">数据分析</span>
                                    </div>
                                    <div class="card-body row">
                                        <div class="list-group col-8" id="analysis">

                                        </div>
                                        <div class="d-none" id="analysis-template">
                                            <div id="analysis-inconsistent" class="list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center">
                                                <span>发现<b>不一致</b>物料数据</span>
                                                <span class="badge badge-danger"></span>
                                            </div>
                                            <div id="analysis-duplicate" class="list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center">
                                                <span>发现<b>重复</b>库存数据</span>
                                                <span class="badge badge-danger"></span>
                                            </div>
                                            <div id="analysis-invalid_qty" class="list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center">
                                                <span>发现<b>无效数量</b>数据</span>
                                                <span class="badge badge-danger"></span>
                                            </div>
                                            <div id="analysis-new_company" class="list-group-item list-group-item-action list-group-item-danger d-flex justify-content-between align-items-center">
                                                <span>发现<b>新公司</b></span>
                                                <span class="badge badge-danger"></span>
                                            </div>
                                            <div id="analysis-new_goods" class="list-group-item list-group-item-action list-group-item-warning d-flex justify-content-between align-items-center">
                                                <span>发现<b>新物料</b>代码</span>
                                                <span class="badge badge-warning"></span>
                                            </div>
                                            <div id="analysis-new_location" class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                                                <span>发现<b>新库位</b></span>
                                                <span class="badge badge-info"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <p><span class="badge badge-danger">-</span> 必须处理</p>
                                            <p><span class="badge badge-warning">-</span> 注意</p>
                                            <p><span class="badge badge-info">-</span> 提示</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm">
                                <div class="card">
                                    <div class="card-header bg-success">
                                        <span class="h5">操作</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <button class="btn btn-primary" id="reload">
                                                    <i class="fas fa-sync"></i> 刷新界面
                                                </button>
                                                <button type="button" class="btn btn-danger" id="cancel">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    取消入库
                                                </button>
                                                <button type="button" class="btn btn-success" id="submit">
                                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                                    提交入库
                                                </button>
                                            </div>
                                            <div class="col-auto mt-auto">
                                                <div class="loading-import">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-bbb card-outline">
                            <div class="card-body">
                                <table class="table table-bordered table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <td>序号</td>
                                            <td>公司</td>
                                            <td>物料代码</td>
                                            <td>物料名称与规格型号</td>
                                            <td>单位</td>
                                            <td>入库量</td>
                                            <td>库位</td>
                                            <td>备注</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- Modal edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">编辑</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-company">公司</label>
                        <input type="text" class="form-control" id="edit-company" placeholder="输入公司" maxlength="255">
                        <div class="invalid-feedback company-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-goods_id">物料代码</label>
                        <input type="text" class="form-control" id="edit-goods_id" placeholder="输入物料代码" maxlength="255">
                        <div class="invalid-feedback goods_id-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-name_type">物料名称与规格型号</label>
                        <input type="text" class="form-control" id="edit-name_type" placeholder="输入物料名称与规格型号" maxlength="255">
                        <div class="invalid-feedback name_type-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-unit">单位</label>
                        <input type="text" class="form-control" id="edit-unit" placeholder="输入单位" maxlength="255">
                        <div class="invalid-feedback unit-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-qty">数量</label>
                        <input type="number" min="0" class="form-control" id="edit-qty" placeholder="输入数量" maxlength="255">
                        <div class="invalid-feedback qty-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-location">库位</label>
                        <input type="text" class="form-control" id="edit-location" placeholder="输入库位" maxlength="255">
                        <div class="invalid-feedback location-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-remark">备注</label>
                        <input type="text" class="form-control" id="edit-remark" placeholder="输入备注" maxlength="255">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                    <button type="button" class="btn btn-primary" id="save-edit"><i class="fas fa-save"></i> 保存</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal duplicate -->
    <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateModalLabel">重复数据</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-duplicate" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>公司</th>
                                <th>物料代码</th>
                                <th>名称与规格型号</th>
                                <th>单位</th>
                                <th>库位</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal new company -->
    <div class="modal fade" id="newCompanyModal" tabindex="-1" role="dialog" aria-labelledby="newCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCompanyModalLabel">新公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-new-company" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>公司</th>
                                <th>物料代码</th>
                                <th>名称与规格型号</th>
                                <th>单位</th>
                                <th>库位</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer text-danger">
                    提示：如果不是输入错误，请联系系统维护员新增新公司数据
                </div>
            </div>
        </div>
    </div>
    <!-- Modal new goods -->
    <div class="modal fade" id="newGoodsModal" tabindex="-1" role="dialog" aria-labelledby="newGoodsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newGoodsModalLabel">新物料</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-new-goods" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>物料代码</th>
                                <th>名称与规格型号</th>
                                <th>单位</th>
                                <th>序号</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer text-danger">
                    提示：入库提交后，物料数据会自动添加进去
                </div>
            </div>
        </div>
    </div>
    <!-- Modal new location -->
    <div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog" aria-labelledby="newLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newLocationModalLabel">新库位</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-new-location" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>库位</th>
                                <th>序号</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer text-danger">
                    提示：入库提交后，物料数据会自动添加进去
                </div>
            </div>
        </div>
    </div>
    <!-- Modal inconsistent -->
    <div class="modal fade" id="inconsistentModal" tabindex="-1" role="dialog" aria-labelledby="inconsistentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inconsistentModalLabel">不一致数据</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-inconsistent" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>物料代码</th>
                                <th>物料名称与规格型号</th>
                                <th>单位</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer text-danger">
                    提示：同一个物料代码但是名称与单位不一致
                </div>
            </div>
        </div>
    </div>
    <!-- Modal invalid qty -->
    <div class="modal fade" id="invalidQtyModal" tabindex="-1" role="dialog" aria-labelledby="invalidQtyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invalidQtyModalLabel">无效数量</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-invalid-qty" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>公司</th>
                                <th>物料代码</th>
                                <th>物料名称与规格型号</th>
                                <th>单位</th>
                                <th>数量</th>
                                <th>库位</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer text-danger">
                    提示：数量不可低于或等于零
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/stock_in_index.js"></script>
<script src="<?= base_url('/'); ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
<?php $this->endSection(); ?>