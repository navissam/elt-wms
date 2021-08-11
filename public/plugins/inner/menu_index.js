$(document).ready(function() {

    // declare global variable
    var menu, parent, parent_select, permission, permission_select;
    var menuID, code_ori, ord_ori;

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
                    data: 'menuID',
                },
                {
                    data: 'code'
                },
                {
                    data: 'name'
                },
                {
                    data: 'parentCode'
                },
                {
                    data: 'type',
                    render: function(data, type) {
                        return (data == 1 ? '目录' : '连接');
                    }
                },
                {
                    data: 'url'
                },
                {
                    data: 'icon'
                },
                {
                    data: 'ord'
                },
                {
                    data: 'perm_name'
                },
                {
                    data: 'status',
                    render: function(data, type) {
                        return (data == 1 ? '可用' : '不可用');
                    }
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'menuID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-menuid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-menuid="' + data + '">';
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
                data: 'menuID',
                },
                {
                    data: 'code'
                },
                {
                    data: 'name'
                },
                {
                    data: 'parentCode'
                },
                {
                    data: 'type',
                    render: function(data, type) {
                        return (data == 1 ? '目录' : '连接');
                    }
                },
                {
                    data: 'url'
                },
                {
                    data: 'icon'
                },
                {
                    data: 'ord'
                },
                {
                    data: 'perm_name'
                },
                {
                    data: 'status',
                    render: function(data, type) {
                        return (data == 1 ? '可用' : '不可用');
                    }
                },
            {
                data: 'deleted_at'
            },
            {
                data: 'menuID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-menuid="' + data + '">';
                        btn += '<i class="fas fa-sync" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // get data menu for add
    function getParent() {
        $.get("/menu/getParent/", function (data) {
            let object = JSON.parse(data);
            parent = $.map(object, function (obj) {
                obj.id = obj.id || obj.code;
                obj.text = obj.text || obj.code;
                return obj;
            });
            parent_select = $(".select2-parent").select2({
                data: parent,
                theme: 'bootstrap4',
                dropdownParent: $("#addModal") 
            });
        });
        }
    
        function getParentEdit() {
        // get data menu for edit
        $.get("/menu/getParent/", function (data) {
            let object = JSON.parse(data);
            parent = $.map(object, function (obj) {
                obj.id = obj.id || obj.code;
                obj.text = obj.text || obj.code;
                return obj;
            });
            parent_select = $(".select2-parent-edit").select2({
                data: parent,
                theme: 'bootstrap4',
                dropdownParent: $("#editModal") 
            });
        });
    }
    
        // get data permission for add
        $.get("/menu/getPermission/", function (data) {
            let object = JSON.parse(data);
            permission = $.map(object, function (obj) {
                obj.id = obj.id || obj.permID;
                obj.text = obj.text || obj.name;
                return obj;
            });
            permission_select = $(".select2-permission").select2({
                data: permission,
                theme: 'bootstrap4',
                dropdownParent: $("#addModal") 
            });
        });
    
        // get data permission for edit
        $.get("/menu/getPermission/", function (data) {
            let object = JSON.parse(data);
            permission = $.map(object, function (obj) {
                obj.id = obj.id || obj.permID;
                obj.text = obj.text || obj.name;
                return obj;
            });
            permission_select = $(".select2-permission-edit").select2({
                data: permission,
                theme: 'bootstrap4',
                dropdownParent: $("#editModal") 
            });
        });

    // reload datasource from ajax
    function reloadTable1() {
        getParent();
        getParentEdit();
        let url = '/menu/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            menu = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = '/menu/getDeleted';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            menu2 = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    $("#add").on("click", function () {
        $("#addModal").modal("show");
    });

    // restore button click event, to show all deleted list
    $('#restore').on('click', function() {
        reloadTable2();
        $('#restoreModal').modal('show');
    });

    // save button click event (for new data)
    $('#save').on('click', function() {
        $(".is-invalid").removeClass("is-invalid");

        $.post('/menu/save', {
            code: $('#add-code').val(),
            name: $('#add-name').val(),
            parentCode: $('#add-parentCode').val(),
            type: $('#add-type').val(),
            url: $('#add-url').val(),
            icon: $('#add-icon').val(),
            ord: $('#add-ord').val(),
            permID: $('#add-permID').val()
        }, function(response) {
            let m = JSON.parse(response);
            if (m.status == 'invalid') {
                for (const [key, value] of Object.entries(m.errors)) {
                    $(`#add-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (m.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    m.msg,
                    'error'
                );
            } else if (m.status == 'success') {
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
            $(".is-invalid").removeClass("is-invalid");

        $.post('/menu/update', {
            menuID: menuID,
            code: $('#edit-code').val(),
            code_ori: code_ori,
            name: $('#edit-name').val(),
            parentCode: $('#edit-parentCode').val(),
            type: $('#edit-type').val(),
            url: $('#edit-url').val(),
            icon: $('#edit-icon').val(),
            ord: $('#edit-ord').val(),
            ord_ori: ord_ori,
            permID: $('#edit-permID').val(),
            status: $('#edit-status').val()
        }, function(response) {
            let m = JSON.parse(response);
            if (m.status == 'invalid') {
                for (const [key, value] of Object.entries(m.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (m.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    m.msg,
                    'error'
                );
            } else if (m.status == 'success') {
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
        $(".is-invalid").removeClass("is-invalid");
        menuID = $(this).data('menuid');
        let m = menu.find(x => x.menuID == menuID);
        let code = m.code;
        let name = m.name;
        let parentCode = m.parentCode;
        let type = m.type;
        let url = m.url;
        let icon = m.icon;
        let ord = m.ord;
        let permID = m.permID;
        let status = m.status;
        code_ori = code;
        ord_ori = ord;
        // console.log(menuID);
        $('#edit-code').val(code);
        $('#edit-name').val(name);
        $('#edit-parentCode').val(parentCode).trigger('change');
        $('#edit-type').val(type);
        $('#edit-url').val(url);
        $('#edit-icon').val(icon);
        $('#edit-ord').val(ord);
        $('#edit-permID').val(permID).trigger('change');
        $('#edit-status').val(status);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        menuID = $(this).data('menuid');
        let m = menu.find(x => x.menuID == menuID);
        let name = m.name;
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
                // $.post('<?= base_url(); ?>/role/delete', {
                $.post('/menu/delete', {
                    menuID: menuID,
                }, function(response) {
                    let m = JSON.parse(response);
                    if (m.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            m.msg,
                            'error'
                        );
                    } else if (m.status == 'success') {
                        Swal.fire(
                            '已删除',
                            name + ' 的路由已经成功删除',
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
        menuID = $(this).data('menuid');
        let m = menu2.find(x => x.menuID == menuID);
        let name = m.name;
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
                // $.post('<?= base_url(); ?>/role/restore', {
                $.post('/menu/restore', {
                    menuID: menuID,
                }, function(response) {
                    let m = JSON.parse(response);
                    if (m.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            m.msg,
                            'error'
                        );
                    } else if (m.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            name + ' 的路由已经成功恢复',
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