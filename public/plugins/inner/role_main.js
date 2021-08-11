$(document).ready(function() {

    // declare global variable
    var role, role2, perm;
    var roleID, name_ori;

    // initialize plugins
    table1 = $("#table1").DataTable();
    table2 = $("#table2").DataTable();
    select2 = $('.select2').select2();

    // function for rebuild datatables (main table)
    function createTable1(obj) {
        table1.destroy();
        table1 = $("#table1").DataTable({
            "responsive": true,
            "autoWidth": false,
            data: obj,
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            columns: [{
                    data: 'roleID',
                },
                {
                    data: 'name'
                },
                {
                    data: 'status',
                    render: function(data, type) {
                        return (data == 1 ? '可用' : '不可用')
                    }
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'roleID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-roleid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-roleid="' + data + '">';
                        btn += '<i class="fa fa-trash" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-info btn-sm btn-assign" data-roleid="' + data + '">';
                        btn += '<i class="fas fa-key" aria-hidden="true"></i>';
                        btn += '</button>';
                        return btn;
                    }
                },
            ],
        });
    }

    // function for rebuild datatables (restore table)
    function createTable2(obj) {
        table2.destroy();
        table2 = $("#table2").DataTable({
            "responsive": true,
            "autoWidth": false,
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
            columns: [{
                    data: 'roleID',
                },
                {
                    data: 'name'
                },
                {
                    data: 'deleted_at'
                },
                {
                    data: 'roleID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-restore" data-roleid="' + data + '">';
                        btn += '<i class="fas fa-sync" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // reload datasource from ajax
    function reloadTable1() {
        // let url = '<?= base_url('/role/getAll/') ?>';
        let url = '/role/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            role = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        // let url = '<?= base_url('/role/getDeleted') ?>';
        let url = '/role/getDeleted';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            role2 = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    // restore button click event, to show all deleted list
    $('#restore').on('click', function() {
        reloadTable2();
        $('#restoreModal').modal('show');
    });

    // save button click event (for new data)
    $('#save').on('click', function() {
        // $.post('<?= base_url(); ?>/role/save', {
        $.post('/role/save', {
            name: $('#add-name').val()
        }, function(response) {
            let r = JSON.parse(response);
            if (r.status == 'invalid') {
                $("#add-name").addClass("is-invalid");
                $(".name-invalid").html(
                    r.errors.name
                );
            } else if (r.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    r.msg,
                    'error'
                );
            } else if (r.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $(".is-invalid").removeClass("is-invalid");
                $('.form-control').val('');
                $('#addModal').modal('toggle');
                reloadTable1();
            }
        });
    });

    // save button click event (for edited data)
    $('#save-edit').on('click', function() {
        // $.post('<?= base_url(); ?>/role/update', {
        $.post('/role/update', {
            roleID: roleID,
            name: $('#edit-name').val(),
            name_ori: name_ori,
            status: $('#edit-status').val(),
        }, function(response) {
            let r = JSON.parse(response);
            if (r.status == 'invalid') {
                $("#edit-name").addClass("is-invalid");
                $(".name-invalid").html(
                    r.errors.name
                );
            } else if (r.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    r.msg,
                    'error'
                );
            } else if (r.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $(".is-invalid").removeClass("is-invalid");
                $('.form-control').val('');
                $('#editModal').modal('toggle');
                reloadTable1();
            }
        });
    });

    // save button click event (for permission assignment)
    $('#save-assign').on('click', function() {
        // $.post('<?= base_url(); ?>/role/assign', {
        $.post('/role/assign', {
            roleID: roleID,
            permIDs: select2.select2('val'),
        }, function(response) {
            let r = JSON.parse(response);
            if (r.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    r.msg,
                    'error'
                );
            } else if (r.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $('#assignModal').modal('toggle');
            }
        });
    });

    // edit button on each row
    $("body").on("click", ".btn-edit", function(e) {
        roleID = $(this).data('roleid');
        let r = role.find(x => x.roleID == roleID);
        let name = r.name;
        let status = r.status;
        name_ori = name;
        $('#edit-name').val(name);
        $('#edit-status').val(status);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        roleID = $(this).data('roleid');
        let r = role.find(x => x.roleID == roleID);
        let name = r.name;
        Swal.fire({
            title: '你确定要删除?',
            text: "角色名称：" + name,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/delete', {
                $.post('/role/delete', {
                    roleID: roleID,
                }, function(response) {
                    let r = JSON.parse(response);
                    if (r.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            r.msg,
                            'error'
                        );
                    } else if (r.status == 'success') {
                        Swal.fire(
                            '已删除',
                            name + ' 的角色已经成功删除',
                            'success'
                        );
                        reloadTable1();
                    }
                });

            }
        })
    });

    // restore button on each row
    $("body").on("click", ".btn-restore", function(e) {
        roleID = $(this).data('roleid');
        let r = role2.find(x => x.roleID == roleID);
        let name = r.name;
        Swal.fire({
            title: '你确定要恢复?',
            text: "角色名称：" + name,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，恢复它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/restore', {
                $.post('/role/restore', {
                    roleID: roleID,
                }, function(response) {
                    let r = JSON.parse(response);
                    if (r.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            r.msg,
                            'error'
                        );
                    } else if (r.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            name + ' 的角色已经成功恢复',
                            'success'
                        );
                        reloadTable1();
                    }
                });
            }
        })
    });

    // assign button on each row, to show permissions list
    $("body").on("click", ".btn-assign", function(e) {
        roleID = $(this).data('roleid');
        let r = role.find(x => x.roleID == roleID);
        let name = r.name;
        // $.get('<?= base_url('/role/permission') ?>/' + roleID, function(data, status) {
        $.get('/role/permission/' + roleID, function(data, status) {
            let object = JSON.parse(data);
            perm = $.map(object, function(obj) {
                obj.id = obj.id || obj.permID;
                obj.text = obj.text || obj.name;
                return obj;
            });
            select2.empty();
            select2.select2({
                data: perm,
            });
        });
        $('#assignModalLabel').html(name + ' 角色权限');
        $('#assignModal').modal('show');
    });

    // enter keypress 
    $('#addModal').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('#save').click();    
        }
    });
    $('#editModal').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('#save-edit').click();    
        }
    });
    $('#assignModal').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('#save-assign').click();    
        }
    });
});