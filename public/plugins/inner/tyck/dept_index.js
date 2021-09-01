$(document).ready(function() {

    // declare global variable
    var dept, company, company_select;
    var deptID;

    // initialize plugins
    table1 = $("#table1").DataTable({
        language: {
            url: '/plugins/inner/datatables-lang.json'
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
    });
    table2 = $("#table2").DataTable({
        language: {
            url: '/plugins/inner/datatables-lang.json'
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
    });

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
                    data: 'deptID',
                },
                {
                    data: 'deptName'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'deptID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-deptid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-deptid="' + data + '">';
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
                    data: 'deptID',
                },
                {
                    data: 'deptName'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'deleted_at'
                },
                {
                data: 'deptID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-deptid="' + data + '">';
                        btn += '<i class="fas fa-sync" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // get data company for add
    $.get("/tyck/dept/getCompany/", function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-company").select2({
            data: company,
            theme: 'bootstrap4',
            dropdownParent: $("#addModal") 
        });
        company_select = $(".select2-company-edit").select2({
            data: company,
            theme: 'bootstrap4',
            dropdownParent: $("#editModal") 
        });
    });

    // reload datasource from ajax
    function reloadTable1() {
        let url = '/tyck/dept/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            dept = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = '/tyck/dept/getDeleted';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            dept2 = obj;
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
        
        $.post('/tyck/dept/save', {
            deptID: $('#add-deptID').val(),
            deptName: $('#add-deptName').val(),
            companyID: $('#add-companyID').val()
        }, 
            function(response) {
            let d = JSON.parse(response);
            if (d.status == 'invalid') {
                for (const [key, value] of Object.entries(d.errors)) {
                    $(`#add-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (d.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    d.msg,
                    'error'
                );
            } else if (d.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $(".is-invalid").removeClass("is-invalid");
                $('.form-control').val('');
                $('#addModal').modal('toggle');
                reloadTable1();
                // location.reload();
            }
        });
    });

    // save button click event (for edited data)
    $('#save-edit').on('click', function() {
            $(".is-invalid").removeClass("is-invalid");

        $.post('/tyck/dept/update', {
            deptID: deptID,
            deptName: $('#edit-deptName').val(),
            companyID: $('#edit-companyID').val()
        }, function(response) {
            let d = JSON.parse(response);
            if (d.status == 'invalid') {
                for (const [key, value] of Object.entries(d.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (d.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    d.msg,
                    'error'
                );
            } else if (d.status == 'success') {
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
        deptID = $(this).data('deptid');
        let d = dept.find(x => x.deptID == deptID);
        let deptName = d.deptName;
        let companyID = d.companyID;
        $('#edit-deptName').val(deptName);
        $('#edit-companyID').val(companyID).trigger('change');
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        deptID = $(this).data('deptid');
        let d = dept.find(x => x.deptID == deptID);
        let deptName = d.deptName;
        Swal.fire({
            title: '你确定要删除?',
            text: "名称：" + deptName,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/delete', {
                $.post('/tyck/dept/delete', {
                    deptID: deptID,
                }, function(response) {
                    let d = JSON.parse(response);
                    if (d.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            d.msg,
                            'error'
                        );
                    } else if (d.status == 'success') {
                        Swal.fire(
                            '已删除',
                            deptName + ' 的部门已经成功删除',
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
        deptID = $(this).data('deptid');
        let d = dept2.find(x => x.deptID == deptID);
        let deptName = d.deptName;
        Swal.fire({
            title: '你确定要恢复?',
            text: "名称：" + deptName,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，恢复它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/restore', {
                $.post('/tyck/dept/restore', {
                    deptID: deptID,
                }, function(response) {
                    let d = JSON.parse(response);
                    if (d.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            d.msg,
                            'error'
                        );
                    } else if (d.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            deptName + ' 的部门已经成功恢复',
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