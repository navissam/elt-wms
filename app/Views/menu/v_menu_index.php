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
                                        <th>菜单ID</th>
                                        <th>编码</th>
                                        <th>名称</th>
                                        <th>父编码</th>
                                        <th>类型</th>
                                        <th>网址</th>
                                        <th>图标</th>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">添加新菜单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="add-code">编码</label>
                                <input type="text" class="form-control" id="add-code" placeholder="输入编码" maxlength="100">
                                <div class="invalid-feedback code-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-name">名称</label>
                                <input type="text" class="form-control" id="add-name" placeholder="输入名称" maxlength="255">
                                <div class="invalid-feedback name-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-parentCode">父编码</label>
                                <select class="form-control select2-parent" id="add-parentCode" style="width: 100%;">
                                    <option value="">空值</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="add-type">类型</label>
                                <select class="form-control" name="add-type" id="add-type">
                                    <option value=1>目录</option>
                                    <option value=2>连接</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="add-url">网址</label>
                                <input type="text" class="form-control" name="add-url" id="add-url" placeholder="输入网址" maxlength="255">
                                <div class="invalid-feedback url-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-icon">图标</label>
                                <input type="text" class="form-control" name="add-icon" id="add-icon" placeholder="输入图标" maxlength="40">
                                <div class="invalid-feedback icon-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-ord">排序</label>
                                <input type="number" min="0" max="2147483647" class="form-control" name="add-ord" id="add-ord" placeholder="输入排序">
                                <div class="invalid-feedback ord-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="add-permID">授权</label>
                                <select class="form-control select2-permission" id="add-permID" style="width: 100%;">
                                    <option value="">空值</option>
                                </select>
                            </div>
                        </div>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">编辑菜单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="edit-code">编码</label>
                                <input type="text" class="form-control" id="edit-code" placeholder="输入编码" maxlength="100">
                                <div class="invalid-feedback code-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-name">名称</label>
                                <input type="text" class="form-control" id="edit-name" placeholder="输入名称" maxlength="255">
                                <div class="invalid-feedback name-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-parentCode">父编码</label>
                                <select class="form-control select2-parent-edit" id="edit-parentCode" style="width: 100%;">
                                    <option value="">空值</option>
                                </select>
                                <div class="invalid-feedback parentCode-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-type">类型</label>
                                <select class="form-control" name="edit-type" id="edit-type">
                                    <option value=1>目录</option>
                                    <option value=2>连接</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="edit-url">网址</label>
                                <input type="text" class="form-control" name="edit-url" id="edit-url" placeholder="输入网址" maxlength="255">
                                <div class="invalid-feedback url-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-icon">图标</label>
                                <input type="text" class="form-control" name="edit-icon" id="edit-icon" placeholder="输入图标" maxlength="40">
                                <div class="invalid-feedback icon-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-ord">排序</label>
                                <!-- <input type="hidden" class="form-control" id="ord_ori" placeholder="输入排序"> -->
                                <input type="number" min="0" max="2147483647" class="form-control" name="edit-ord" id="edit-ord" placeholder="输入排序">
                                <div class="invalid-feedback ord-invalid">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-permID">授权</label>
                                <select class="form-control select2-permission-edit" id="edit-permID" style="width: 100%;">
                                    <option value="">空值</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">状态</label>
                        <select class="form-control" name="edit-status" id="edit-status">
                            <option value=1>可用</option>
                            <option value=0>不可用</option>
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
                    <h5 class="modal-title" id="restoreModalLabel">已删菜单</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>菜单ID</th>
                                <th>编码</th>
                                <th>名称</th>
                                <th>父编码</th>
                                <th>类型</th>
                                <th>网址</th>
                                <th>图标</th>
                                <th>排序</th>
                                <th>授权</th>
                                <th>状态</th>
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
<script src="<?= base_url('/'); ?>/plugins/inner/menu_index.js"></script>
<?php $this->endSection(); ?>