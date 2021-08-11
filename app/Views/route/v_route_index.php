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
                <div class="col">
                    <!-- Default box -->
                    <div class="card card-bbb card-outline">
                        <div class="card-header">
                            <button class="btn bg-bbb" data-toggle="modal" id="add">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                添加
                            </button>
                            <button class="btn btn-warning " id="restore">
                                <i class="fa fa-sync" aria-hidden="true"></i>
                                恢复
                            </button>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="table1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>路由ID</th>
                                        <th>httpMethod</th>
                                        <th>网址</th>
                                        <th>contMeth</th>
                                        <th>排序</th>
                                        <th>授权</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>更新时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <!-- Modal add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">添加新路由</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-httpMethod">httpMethod</label>
                        <input type="text" class="form-control" id="add-httpMethod" placeholder="输入httpMethod" maxlength="6">
                        <div class="invalid-feedback httpMethod-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-url">网址</label>
                        <input type="text" class="form-control" id="add-url" placeholder="输入网址" maxlength="255">
                        <div class="invalid-feedback url-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-contMeth">contMeth</label>
                        <input type="text" class="form-control" id="add-contMeth" placeholder="输入contMeth" maxlength="255">
                        <div class="invalid-feedback contMeth-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-ord">排序</label>
                        <input type="number" class="form-control" id="add-ord" placeholder="输入排序" maxlength="2147483647">
                        <div class="invalid-feedback ord-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-permID">授权ID</label>
                        <select class="form-control select2-permission" id="add-permID" style="width: 100%;">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                    <button type="button" class="btn btn-primary" id="save"><i class="fas fa-save"></i> 保存</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">编辑路由</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-httpMethod">httpMethod</label>
                        <input type="text" class="form-control" id="edit-httpMethod" placeholder="输入httpMethod" maxlength="6">
                        <div class="invalid-feedback httpMethod-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-url">网址</label>
                        <input type="text" class="form-control" id="edit-url" placeholder="输入网址" maxlength="255">
                        <div class="invalid-feedback url-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-contMeth">contMeth</label>
                        <input type="text" class="form-control" id="edit-contMeth" placeholder="输入contMeth" maxlength="255">
                        <div class="invalid-feedback contMeth-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-ord">排序</label>
                        <input type="number" class="form-control" id="edit-ord" placeholder="输入排序" maxlength="2147483647">
                        <div class="invalid-feedback ord-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-permID">授权ID</label>
                        <select class="form-control select2-permission-edit" id="edit-permID" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">状态</label>
                        <select id="edit-status" class="form-control">
                            <option value="1">可用</option>
                            <option value="0">不可用</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                    <button type="button" class="btn btn-primary" id="save-edit"><i class="fas fa-save"></i> 保存</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal restore -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">已删路由</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>路由ID</th>
                                <th>httpMethod</th>
                                <th>网址</th>
                                <th>contMeth</th>
                                <th>排序</th>
                                <th>权限ID</th>
                                <th>已删时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                        <button type="button" class="btn btn-primary" id="save-edit"><i class="fas fa-save"></i> 保存</button>
                    </div> -->
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
<script src="<?= base_url('/'); ?>/plugins/inner/route_index.js"></script>
<?php $this->endSection(); ?>