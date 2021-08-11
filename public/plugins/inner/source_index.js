$(document).ready(function() {

    // declare global variable
    var goods_src;
    var sourceID;

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
                    data: 'sourceID',
                },
                {
                    data: 'name'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'remark'
                },
                {
                    data: 'sourceID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-sourceid="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-sourceid="' + data + '">';
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
            },
            data: obj,
            columns: [{
                data: 'sourceID',
            },
            {
                data: 'name'
            },
            {
                data: 'deleted_at'
            },
            {
                data: 'remark'
            },
            {
                data: 'sourceID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-sourceid="' + data + '">';
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
        let url = '/goods_src/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            source = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = '/goods_src/getDeleted';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            source2 = obj;
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
        $("#name-msg").hide();
        $(".is-invalid").removeClass("is-invalid");

        $.post('/goods_src/save', {
            name: $('#add-name').val(),
            remark: $('#add-remark').val()
        }, function(response) {
            let s = JSON.parse(response);
            if (s.status == 'name-inv') {
                $("#add-name").addClass("is-invalid");
                $(".name-invalid").html(
                    s.errors.name
                    );
                $("#name-msg").show();
            } else if (s.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    s.msg,
                    'error'
                );
            } else if (s.status == 'success') {
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
            // $("#remark-msg").hide();
            $(".is-invalid").removeClass("is-invalid");

        $.post('/goods_src/update', {
            sourceID: sourceID,
            name: $('#edit-name').val(),
            remark: $('#edit-remark').val(),
        }, function(response) {
            let s = JSON.parse(response);
            if (s.status == 'name-inv') {
                $("#edit-name").addClass("is-invalid");
                $(".name-invalid").html(
                    s.errors.name
                    );
                $("#name-msg").show();
            // } else if (s.status == 'remark-inv') {
            //     $("#edit-remark").addClass("is-invalid");
            //     $(".remark-invalid").html(
            //         s.errors.remark
            //         );
            //         $("#remark-msg").show();
            } else if (s.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    s.msg,
                    'error'
                );
            } else if (s.status == 'success') {
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
        sourceID = $(this).data('sourceid');
        let s = source.find(x => x.sourceID == sourceID);
        let name = s.name;
        let remark = s.remark;
        $('#edit-name').val(name);
        $('#edit-remark').val(remark);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function(e) {
        sourceID = $(this).data('sourceid');
        let s = source.find(x => x.sourceID == sourceID);
        let name = s.name;
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
                $.post('/goods_src/delete', {
                    sourceID: sourceID,
                }, function(response) {
                    let s = JSON.parse(response);
                    if (s.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            s.msg,
                            'error'
                        );
                    } else if (s.status == 'success') {
                        Swal.fire(
                            '已删除',
                            name + ' 物资来源已经成功删除',
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
        sourceID = $(this).data('sourceid');
        let s = source2.find(x => x.sourceID == sourceID);
        let name = s.name;
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
                $.post('/goods_src/restore', {
                    sourceID: sourceID,
                }, function(response) {
                    let s = JSON.parse(response);
                    if (s.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            s.msg,
                            'error'
                        );
                    } else if (s.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            name + ' 物资来源已经成功恢复',
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