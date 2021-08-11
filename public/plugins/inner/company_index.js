$(document).ready(function () {

    // declare global variable
    var company;
    var companyID;

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
                data: 'companyID',
            },
            {
                data: 'nameInd'
            },
            {
                data: 'nameMan'
            },
            {
                data: 'logo',
                render: function (data, type) {
                    return ('<img src="/img/' + data + '" width="70px">')
                }
            },
            {
                data: 'created_at'
            },
            {
                data: 'updated_at'
            },
            {
                data: 'companyID',
                render: function (data, type) {
                    let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-companyid="' + data + '">';
                    btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                    btn += '</button> ';
                    btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-companyid="' + data + '">';
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
                data: 'companyID',
            },
            {
                data: 'nameInd'
            },
            {
                data: 'nameMan'
            },
            {
                data: 'logo',
                render: function (data) {
                    return ('<img src="/img/' + data + '" width="50px">')
                }
            },
            {
                data: 'deleted_at'
            },
            {
                data: 'companyID',
                render: function (data, type) {
                    let btn = '<button type="button" class="btn bg-bbb btn-sm btn-restore" data-companyid="' + data + '">';
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
        // let url = '<?= base_url('/company/getAll/') ?>';
        let url = '/company/getAll/';
        $.get(url, function (data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            company = obj;
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        // let url = '<?= base_url('/company/getDeleted') ?>';
        let url = '/company/getDeleted';
        $.get(url, function (data, status) {
            let obj = JSON.parse(data);
            createTable2(obj);
            company2 = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    $("#add").on("click", function () {
        $("#addModal").modal("show");
    });

    // restore button click event, to show all deleted list
    $('#restore').on('click', function () {
        reloadTable2();
        $('#restoreModal').modal('show');
    });


    // save button click event (for new data)
    $('#save').on('click', function () {
        $(".is-invalid").removeClass("is-invalid");

        var form_data = new FormData();
        console.log($('#add-logo').prop('files').length);
        if ($('#add-logo').prop('files').length != 0) {
            var file_data = $('#add-logo').prop('files')[0];
            form_data.append('logo', file_data);
        }
        form_data.append('companyID', $('#add-companyID').val());
        form_data.append('nameInd', $('#add-nameInd').val());
        form_data.append('nameMan', $('#add-nameMan').val());
        console.log(form_data.get('logo'));
        $.ajax({
            url: '/company/save', // point to server-side PHP script
            // dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                let r = JSON.parse(response);
                // r = response;
                if (r.status == 'invalid') {
                    for (const [key, value] of Object.entries(r.errors)) {
                        console.log(`${key}: ${value}`);
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
            }
        });
    });

    // save button click event (for edited data)
    $('#save-edit').on('click', function () {
        // $.post('<?= base_url(); ?>/company/update', {
        $("#nameInd-msg").hide();
        $("#nameMan-msg").hide();
        $("#logo-msg").hide();
        $(".is-invalid").removeClass("is-invalid");

        var form_data = new FormData();
        if ($('#edit-logo').prop('files').length != 0) {
            var file_data = $('#edit-logo').prop('files')[0];
            form_data.append('logo', file_data);
        }
        form_data.append('companyID', $('#edit-companyID').val());
        form_data.append('nameInd', $('#edit-nameInd').val());
        form_data.append('nameMan', $('#edit-nameMan').val());
        form_data.append('oldLogo', $('#oldLogo').val());
        $.ajax({
            url: '/company/update', // point to server-side PHP script
            // dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                let r = JSON.parse(response);
                // r = response;
                if (r.status == 'invalid') {
                    for (const [key, value] of Object.entries(r.errors)) {
                        console.log(`${key}: ${value}`);
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
                    // $(".is-invalid").removeClass("is-invalid");
                    // $('.form-control').val('');
                    // $('#editModal').modal('toggle');
                    // reloadTable1();
                    setTimeout(function () {
                        location.reload(true);
                    }, 1500);
                }
            }
        });

    });

    // edit button on each row
    $("body").on("click", ".btn-edit", function (e) {
        $(".is-invalid").removeClass("is-invalid");
        companyID = $(this).data('companyid');
        let c = company.find(x => x.companyID == companyID);
        let nameInd = c.nameInd;
        let nameMan = c.nameMan;
        let logo = c.logo;
        $('#edit-companyID').val(companyID);
        $('#edit-nameInd').val(nameInd);
        $('#edit-nameMan').val(nameMan);
        $('.custom-file-label.edit').html(logo);
        $('#imgP').attr('src', '/img/' + logo);
        $('#oldLogo').val(logo);
        $('#editModal').modal('show');
    });

    // delete button on each row
    $("body").on("click", ".btn-delete", function (e) {
        companyID = $(this).data('companyid');
        let c = company.find(x => x.companyID == companyID);
        let nameMan = c.nameMan;
        Swal.fire({
            title: '你确定要删除?',
            text: "名称：" + nameMan,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/delete', {
                $.post('/company/delete', {
                    companyID: companyID,
                }, function (response) {
                    let c = JSON.parse(response);
                    if (c.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            c.msg,
                            'error'
                        );
                    } else if (c.status == 'success') {
                        Swal.fire(
                            '已删除',
                            name + ' 公司已经成功删除',
                            'success'
                        );
                        reloadTable1();
                    }
                });

            }
        })
    });

    // restore button on each row
    $("body").on("click", ".btn-restore", function (e) {
        companyID = $(this).data('companyid');
        let c = company2.find(x => x.companyID == companyID);
        let nameMan = c.nameMan;
        Swal.fire({
            title: '你确定要恢复?',
            text: "名称：" + nameMan,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，恢复它！'
        }).then((result) => {
            if (result.isConfirmed) {
                // $.post('<?= base_url(); ?>/role/restore', {
                $.post('/company/restore', {
                    companyID: companyID,
                }, function (response) {
                    let c = JSON.parse(response);
                    if (c.status == 'error') {
                        Swal.fire(
                            '恢复失败!',
                            c.msg,
                            'error'
                        );
                    } else if (c.status == 'success') {
                        $('#restoreModal').modal('toggle');
                        Swal.fire(
                            '已恢复',
                            name + ' 公司已经成功恢复',
                            'success'
                        );
                        reloadTable1();
                    }
                });
            }
        })
    });

    // enter keypress 
    $('#addModal').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#save').click();
        }
    });
    $('#editModal').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#save-edit').click();
        }
    });
});