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
                            <!-- <button class="btn btn-warning " id="restore">
                                <i class="fa fa-sync" aria-hidden="true"></i>
                                恢复
                            </button> -->
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="table1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>用户ID</th>
                                        <th>工号</th>
                                        <th>名称</th>
                                        <th>角色</th>
                                        <th>部门</th>
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
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- /.card -->
    <!-- Modal add -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">添加新用户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-empID">工号</label>
                        <input type="text" class="form-control" id="add-empID" placeholder="输入工号" maxlength="12">
                        <div class="invalid-feedback empID-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-name">名称</label>
                        <input type="text" class="form-control" id="add-name" placeholder="输入名称" maxlength="255">
                        <div class="invalid-feedback name-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-roleID">角色</label>
                        <select class="form-control select2-role" id="add-roleID" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add-deptID">部门</label>
                        <select class="form-control select2-dept" id="add-deptID" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add-password">密码</label>
                        <input type="password" class="form-control" id="add-password" placeholder="输入密码" maxlength="255">
                        <div class="invalid-feedback password-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-passConfirm">重复密码</label>
                        <input type="password" class="form-control" id="add-passConfirm" placeholder="重复输入密码" maxlength="255">
                        <div class="invalid-feedback passConfirm-invalid">
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">编辑用户</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-empID">工号</label>
                        <!-- <input type="hidden" class="form-control" id="empID_ori" placeholder="输入工号" maxlength="12"> -->
                        <input type="text" class="form-control" id="edit-empID" placeholder="输入工号" maxlength="12">
                        <div class="invalid-feedback empID-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-name">名称</label>
                        <input type="text" class="form-control" id="edit-name" placeholder="输入名称" maxlength="255">
                        <div class="invalid-feedback name-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-roleID">角色</label>
                        <select class="form-control select2-role-edit" id="edit-roleID" style="width: 100%;">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-deptID">部门</label>
                        <select class="form-control select2-dept-edit" id="edit-deptID" style="width: 100%;">
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
<script src="<?= base_url('/'); ?>/plugins/inner/users_index.js"></script>
<?php $this->endSection(); ?>