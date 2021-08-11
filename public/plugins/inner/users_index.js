$(document).ready(function() {

    // declare global variable
    var user, role, role_select, dept, dept_select;
    var userID, empID_ori;
    
    // initialize plugins
    table1 = $("#table1").DataTable();

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
                    data: 'userID',
                },
                {
                    data: 'empID'
                },
                {
                    data: 'name'
                },
                {
                    data: 'role_name'
                },
                {
                    data: 'dept_name'
                },
                {
                    data: 'status',
                    render: function(data, type) {
                        return (data == 1 ? '<span class="text-success">正常</span>' : '<span class="text-danger">已拉黑</span>')
                    }
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'concate',
                    render: function(data, type) {
                        data = data.split('-');
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-userid="' + data[0] + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        if (data[1] == 1) {
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-blacklist" data-userid="' + data[0] + '">';
                        btn += '<i class="fa fa-trash" aria-hidden="true"></i>';
                        btn += '</button> ';       
                        } else {               
                        btn += '<button type="button" class="btn btn-success btn-sm btn-restore" data-userid="' + data[0] + '">';
                        btn += '<i class="fa fa-trash-restore" aria-hidden="true"></i>';
                        btn += '</button> ';
                        }
                        btn += '<button type="button" class="btn btn-info btn-sm btn-reset" data-userid="' + data[0] + '">';
                        btn += '<i class="fa fa-sync" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    },
                }
            ],
        });
    }

    // get data role for add
    $.get("/user/getRole/", function (data) {
        let object = JSON.parse(data);
        role = $.map(object, function (obj) {
            obj.id = obj.id || obj.roleID;
            obj.text = obj.text || obj.name;
            return obj;
        });
        role_select = $(".select2-role").select2({
            data: role,
            theme: 'bootstrap4',
            dropdownParent: $("#addModal") 
        });
    });

    // get data role for edit
    $.get("/user/getRole/", function (data) {
        let object = JSON.parse(data);
        role = $.map(object, function (obj) {
            obj.id = obj.id || obj.roleID;
            obj.text = obj.text || obj.name;
            return obj;
        });
        role_select = $(".select2-role-edit").select2({
            data: role,
            theme: 'bootstrap4',
            dropdownParent: $("#editModal") 
        });
    });

    // get data dept for add
    $.get("/user/getDept/", function (data) {
        let object = JSON.parse(data);
        dept = $.map(object, function (obj) {
            obj.id = obj.id || obj.deptID;
            obj.text = obj.text || obj.deptName;
            return obj;
        });
        dept_select = $(".select2-dept").select2({
            data: dept,
            theme: 'bootstrap4',
            dropdownParent: $("#addModal") 
        });
    });

    // get data dept for edit
    $.get("/user/getDept/", function (data) {
        let object = JSON.parse(data);
        dept = $.map(object, function (obj) {
            obj.id = obj.id || obj.deptID;
            obj.text = obj.text || obj.deptName;
            return obj;
        });
        dept_select = $(".select2-dept-edit").select2({
            data: dept,
            theme: 'bootstrap4',
            dropdownParent: $("#editModal") 
        });
    });


    // reload datasource from ajax
    function reloadTable1() {
        let url = '/user/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            user = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    $("#add").on("click", function () {
        $(".is-invalid").removeClass("is-invalid");
        $("#addModal").modal("show");
    });

    // save button click event (for new data)
    $('#save').on('click', function() {
        $(".is-invalid").removeClass("is-invalid");

        $.post('/user/save', {
            empID: $('#add-empID').val(),
            name: $('#add-name').val(),
            roleID: $('#add-roleID').val(),
            deptID: $('#add-deptID').val(),
            password: $('#add-password').val(),
            passConfirm: $('#add-passConfirm').val()
        }, function(response) {
            let u = JSON.parse(response);
            if (u.status == 'invalid') {
                for (const [key, value] of Object.entries(u.errors)) {
                    $(`#add-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (u.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    u.msg,
                    'error'
                );
            } else if (u.status == 'success') {
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

        $.post('/user/update', {
            userID: userID,
            empID: $('#edit-empID').val(),
            empID_ori: empID_ori,
            name: $('#edit-name').val(),
            roleID: $('#edit-roleID').val(),
            deptID: $('#edit-deptID').val(),
        }, function(response) {
            let u = JSON.parse(response);
            if (u.status == 'invalid') {
                for (const [key, value] of Object.entries(u.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (u.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    u.msg,
                    'error'
                );
            } else if (u.status == 'success') {
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
        userID = $(this).data('userid');
        let u = user.find(x => x.userID == userID);
        let empID = u.empID;
        let name = u.name;
        let roleID = u.roleID;
        let deptID = u.deptID;
        empID_ori = empID;
        // console.log(userID);
        $('#edit-empID').val(empID);
        $('#edit-name').val(name);
        $('#edit-roleID').val(roleID).trigger('change');
        $('#edit-deptID').val(deptID).trigger('change');
        $('#editModal').modal('show');
    });

    // blacklist button on each row
    $("body").on("click", ".btn-blacklist", function(e) {
        userID = $(this).data('userid');
        let u = user.find(x => x.userID == userID);
        let name = u.name;
        Swal.fire({
            title: '你确定要拉黑?',
            text: "名称：" + name,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，拉黑它！'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/user/blacklist', {
                    userID: userID,
                }, function(response) {
                    let u = JSON.parse(response);
                    if (u.status == 'error') {
                        Swal.fire(
                            '拉黑失败!',
                            u.msg,
                            'error'
                        );
                    } else if (u.status == 'success') {
                        Swal.fire(
                            '已拉黑',
                            name + ' 的用户已经成功拉黑',
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
        userID = $(this).data('userid');
        let u = user.find(x => x.userID == userID);
        let name = u.name;
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
                $.post('/user/restore', {
                    userID: userID,
                }, function(response) {
                    let u = JSON.parse(response);
                    if (u.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            u.msg,
                            'error'
                        );
                    } else if (u.status == 'success') {
                        Swal.fire(
                            '已恢复',
                            name + ' 的用户已经成功恢复',
                            'success'
                        );
                        reloadTable1();
                    }
                });
            }
        })
    });

// reset button on each row
    $("body").on("click", ".btn-reset", function(e) {
        userID = $(this).data('userid');
        let u = user.find(x => x.userID == userID);
        let name = u.name;
        Swal.fire({
            title: '你确定要重置密码?',
            text: "名称：" + name,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，重置它！'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/user/reset', {
                    userID: userID,
                }, function(response) {
                    let u = JSON.parse(response);
                    if (u.status == 'error') {
                        Swal.fire(
                            '重置失败!',
                            u.msg,
                            'error'
                        );
                    } else if (u.status == 'success') {
                        Swal.fire(
                            '已重置',
                            name + ' 的密码已经成功重置， 新密码是 "123"',
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