$(document).ready(function() {

    // declare global variable
    var route, permission, permission_select;
    var routeID;

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
                    data: 'routeID',
                    render: function(data) {
                        return Number(data);
                    }
                },
                {
                    data: 'httpMethod'
                },
                {
                    data: 'url'
                },
                {
                    data: 'contMeth'
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
                    data: 'routeID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-routeid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-routeid="' + data + '">';
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
                data: 'routeID',
            },
            {
                data: 'httpMethod'
            },
            {
                data: 'url'
            },
            {
                data: 'contMeth'
            },
            {
                data: 'ord'
            },
            {
                data: 'perm_name'
            },
            {
                data: 'deleted_at'
            },
            {
                data: 'routeID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-routeid="' + data + '">';
                        btn += '<i class="fas fa-sync" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // get data permission for add
    $.get("/route/getPermission/", function (data) {
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
    $.get("/route/getPermission/", function (data) {
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
        let url = '/route/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            route = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = '/route/getDeleted';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            route2 = obj;
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

        $.post('/route/save', {
            httpMethod: $('#add-httpMethod').val(),
            url: $('#add-url').val(),
            contMeth: $('#add-contMeth').val(),
            ord: $('#add-ord').val(),
            permID: $('#add-permID').val()
        }, function(response) {
            let r = JSON.parse(response);
            if (r.status == 'invalid') {
                for (const [key, value] of Object.entries(r.errors)) {
                    $(`#add-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
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
            $(".is-invalid").removeClass("is-invalid");

        $.post('/route/update', {
            routeID: routeID,
            httpMethod: $('#edit-httpMethod').val(),
            url: $('#edit-url').val(),
            contMeth: $('#edit-contMeth').val(),
            httpMethod: $('#edit-httpMethod').val(),
            ord: $('#edit-ord').val(),
            permID: $('#edit-permID').val(),
            status: $('#edit-status').val()
        }, function(response) {
            let r = JSON.parse(response);
            if (r.status == 'invalid') {
                for (const [key, value] of Object.entries(r.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
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

    // edit button on each row
    $("body").on("click", ".btn-edit", function(e) {
        $(".is-invalid").removeClass("is-invalid");
        routeID = $(this).data('routeid');
        let r = route.find(x => x.routeID == routeID);
        let httpMethod = r.httpMethod;
        let url = r.url;
        let contMeth = r.contMeth;
        let ord = r.ord;
        let permID = r.permID;
        let status = r.status;
        // console.log(permID);
        $('#edit-httpMethod').val(httpMethod);
        $('#edit-url').val(url);
        $('#edit-contMeth').val(contMeth);
        $('#edit-ord').val(ord);
        $('#edit-permID').val(permID).trigger('change');
        $('#edit-status').val(status);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        routeID = $(this).data('routeid');
        let r = route.find(x => x.routeID == routeID);
        let contMeth = r.contMeth;
        Swal.fire({
            title: '你确定要删除?',
            text: "名称：" + contMeth,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/delete', {
                $.post('/route/delete', {
                    routeID: routeID,
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
                            contMeth + ' 的路由已经成功删除',
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
        routeID = $(this).data('routeid');
        let r = route2.find(x => x.routeID == routeID);
        let contMeth = r.contMeth;
        Swal.fire({
            title: '你确定要恢复?',
            text: "名称：" + contMeth,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，恢复它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/restore', {
                $.post('/route/restore', {
                    routeID: routeID,
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
                            contMeth + ' 的路由已经成功恢复',
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