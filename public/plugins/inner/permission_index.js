$(document).ready(function() {

    // declare global variable
    var permission;
    var permID, name_ori;

    // initialize plugins
    table1 = $("#table1").DataTable();
    table2 = $("#table2").DataTable();

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
                    data: 'permID',
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
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'permID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-permid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-permid="' + data + '">';
                        btn += '<i class="fa fa-trash" aria-hidden="true"></i>';
                        btn += '</button> ';
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
                    data: 'permID',
                },
                {
                    data: 'name'
                },
                {
                    data: 'deleted_at'
                },
                {
                    data: 'permID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-permid="' + data + '">';
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
        let url = '/permission/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            permission = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = '/permission/getDeleted';
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
        $(".is-invalid").removeClass("is-invalid");

        $.post('/permission/save', {
            name: $('#add-name').val(),
            status: $('#add-status').val()
        }, function(response) {
            let p = JSON.parse(response);
            if (p.status == 'name-inv') {
                $("#add-name").addClass("is-invalid");
                $(".name-invalid").html(
                    p.errors.name
                    );
                $("#name-msg").show();
            } else if (p.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    p.msg,
                    'error'
                );
            } else if (p.status == 'success') {
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
        $("#name-msg").hide();
        $(".is-invalid").removeClass("is-invalid");

        $.post('/permission/update', {
            permID: permID,
            name: $('#edit-name').val(),
            name_ori: name_ori,
            status: $('#edit-status').val(),
        }, function(response) {
            let p = JSON.parse(response);
            if (p.status == 'name-inv') {
                $("#edit-name").addClass("is-invalid");
                $(".name-invalid").html(
                    p.errors.name
                    );
                $("#name-msg").show();
            } else if (p.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    p.msg,
                    'error'
                );
            } else if (p.status == 'success') {
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

    // edit button on each row
    $("body").on("click", ".btn-edit", function(e) {
        permID = $(this).data('permid');
        let p = permission.find(x => x.permID == permID);
        let name = p.name;
        let status = p.status;
        name_ori = name;
        $('#edit-name').val(name);
        $('#edit-status').val(status);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        permID = $(this).data('permid');
        let p = permission.find(x => x.permID == permID);
        let name = p.name;
        Swal.fire({
            title: '你确定要删除?',
            text: "名称：" + name,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/permission/delete', {
                $.post('/permission/delete', {
                    permID: permID,
                }, function(response) {
                    let p = JSON.parse(response);
                    if (p.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            p.msg,
                            'error'
                        );
                    } else if (p.status == 'success') {
                        Swal.fire(
                            '已删除',
                            name + ' 的授权已经成功删除',
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
        permID = $(this).data('permid');
        let p = role2.find(x => x.permID == permID);
        let name = p.name;
        Swal.fire({
            title: '你确定要恢复?',
            text: "名称：" + name,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，恢复它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/permission/restore', {
                $.post('/permission/restore', {
                    permID: permID,
                }, function(response) {
                    let p = JSON.parse(response);
                    if (p.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            p.msg,
                            'error'
                        );
                    } else if (p.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            name + ' 的授权已经成功恢复',
                            'success'
                        );
                        reloadTable1();
                    }
                });
            }
        })
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
});