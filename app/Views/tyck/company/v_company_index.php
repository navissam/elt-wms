<?= $this->extend('templates/index'); ?>

<?= $this->section('style') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('/'); ?>/plugins/select2/css/select2.min.css">
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
                                        <th>公司ID</th>
                                        <th>印尼名称</th>
                                        <th>中文名称</th>
                                        <th>商标</th>
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
                    <h5 class="modal-title" id="addModalLabel">添加新公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="myForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add-companyID">公司ID</label>
                            <input type="text" class="form-control" id="add-companyID" placeholder="输入公司ID" maxlength="5">
                            <div class="invalid-feedback companyID-invalid">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="add-nameInd">印尼名称</label>
                            <input type="text" class="form-control" id="add-nameInd" placeholder="输入印尼名称" maxlength="255">
                            <div class="invalid-feedback nameInd-invalid">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="add-nameMan">中文名称</label>
                            <input type="text" class="form-control" id="add-nameMan" placeholder="输入中文名称" maxlength="255">
                            <div class="invalid-feedback nameMan-invalid">
                            </div>
                        </div>
                        <label>商标</label>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <img src="/img/default.png" class="img-thumbnail img-preview">
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="logo" id="add-logo" onchange="previewImg()">
                                    <label class="custom-file-label" for="add-logo">选择商标。。。</label>
                                    <div class="invalid-feedback logo-invalid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> 取消</button>
                        <button type="button" class="btn btn-primary" id="save"><i class="fas fa-save"></i> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">编辑公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-nameInd">印尼名称</label>
                        <input type="hidden" id="edit-companyID">
                        <input type="text" class="form-control" id="edit-nameInd" placeholder="输入印尼名称" maxlength="255">
                        <div class="invalid-feedback nameInd-invalid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-nameMan">中文名称</label>
                        <input type="text" class="form-control" id="edit-nameMan" placeholder="输入中文名称" maxlength="255">
                        <div class="invalid-feedback nameMan-invalid">
                        </div>
                    </div>
                    <label for="edit-logo">商标</label>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <img src="/img/default.png" class="img-thumbnail img-preview edit" id="imgP">
                        </div>
                        <div class="col-sm-9">
                            <div class="custom-file">
                                <input type="hidden" id="oldLogo">
                                <input type="file" class="custom-file-input" id="edit-logo" name="edit-logo" onchange="previewImgEdit()">
                                <label class="custom-file-label edit" for="edit-logo"></label>
                                <div class="invalid-feedback logo-invalid">
                                </div>
                            </div>
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

    <!-- Modal restore -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">已删公司</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>公司ID</th>
                                <th>印尼名称</th>
                                <th>中文名称</th>
                                <th>商标</th>
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
<script src="<?= base_url('/'); ?>/plugins/inner/tyck/company_index.js"></script>
<!-- <script>
    function uploadLogo() {
        // var logoName = $('#add-logo').val();
        // console.log(logoName);
        var logoData = $('.custom-file-input').prop('files')[0];
        console.log(logoData);
        var formdata = new FormData();
        formdata.append('logo', logoData);
        // formdata.append('file', logoName);

        // var logoName = document.getElementById(".custom-file-input");
        // console.log(logoName);
        // formdata = new FormData(logoName);

        $.ajax({
            url: '/company/save',
            type: 'POST',
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            data: formdata
        });
    } -->
</script>
<?php $this->endSection(); ?>