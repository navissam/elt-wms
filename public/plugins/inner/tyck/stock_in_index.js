$(document).ready(function () {
    // declare analysis
    function reload_analysis() {
        $.get("/tyck/stock_in/analysis", function (data) {
            $("#analysis").html($("#analysis-template").html());
            data = JSON.parse(data);
            Object.entries(data.data).forEach(([key, value]) => {
                $("#analysis-" + key + " .badge").html(value);
                if (value > 0)
                    $("#analysis-" + key).show();
                else
                    $("#analysis-" + key).remove();
            });
            if (data.data.inconsistent > 0 || data.data.invalid_qty > 0 || data.data.new_company > 0 || data.data.duplicate > 0) {
                $("#submit").prop("disabled", true);
            } else {
                $("#submit").prop("disabled", false);
            }
        });
    }

    reload_analysis();
    $("#reload").click(function () {
        reload_analysis();
    })

    table1 = $("#table1").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        // ajax: {
        //     url: '/stock_in/temp',
        //     dataSrc: ''
        // },
        ajax: '/tyck/stock_in/temp',
        columns: [{
                data: 'num',
                type: 'num'
            },
            {
                data: 'company',
            },
            {
                data: 'goods_id',
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
            {
                data: 'qty',
                type: 'num'
            },
            {
                data: 'location'
            },
            {
                data: 'remark'
            },
            {
                data: 'sti_id',
                render: function (data) {
                    let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-sti-id="' + data + '">';
                    btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                    btn += '</button> ';
                    btn += '<button type="button" class="btn btn-danger btn-sm btn-delete" data-sti-id="' + data + '">';
                    btn += '<i class="fas fa-trash" aria-hidden="true"></i>';
                    btn += '</button>';
                    return btn;
                }
            }
        ],
    });
    table_duplicate = $("#table-duplicate").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/duplicate',
        columns: [{
                data: 'num',
                render: function (data) {
                    return '<span class="text-danger">' + data + '</span>';
                }
            },
            {
                data: 'company',
            },
            {
                data: 'goods_id',
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
            {
                data: 'location'
            },

        ],
    });
    table_new_company = $("#table-new-company").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/new_company',
        columns: [{
                data: 'num',
            },
            {
                data: 'company',
                render: function (data) {
                    return '<span class="text-danger">' + data + '</span>';
                }
            },
            {
                data: 'goods_id',
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
            {
                data: 'location'
            },
            {
                data: 'remark'
            },

        ],
    });
    table_new_goods = $("#table-new-goods").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/new_goods',
        columns: [{
                data: 'goods_id',
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
            {
                data: 'num'
            },
        ],
    });
    table_new_location = $("#table-new-location").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/new_location',
        columns: [{
                data: 'location'
            },
            {
                data: 'num'
            },
        ],
    });
    table_inconsistent = $("#table-inconsistent").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/inconsistent',
        columns: [{
                data: 'num'
            },
            {
                data: 'goods_id'
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
        ],
    });
    table_invalid_qty = $("#table-invalid-qty").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_in/invalid_qty',
        columns: [{
                data: 'num'
            },
            {
                data: 'company'
            },
            {
                data: 'goods_id'
            },
            {
                data: 'name_type'
            },
            {
                data: 'unit'
            },
            {
                data: 'qty',
                render: function (data) {
                    return '<span class="text-danger">' + data + '</span>'
                }
            },
            {
                data: 'location'
            },
        ],
    });




    $('#upload').click(function () {
        $(".is-invalid").removeClass("is-invalid");
        $(this).addClass("disabled");
        $(this).prop('disabled', true);
        $('.loading-upload').addClass('spinner-border');
        var btn = this;
        var form_data = new FormData();
        if ($('#file').prop('files').length != 0) {
            var file_data = $('#file').prop('files')[0];
            form_data.append('file', file_data);
        }
        $.ajax({
            url: '/tyck/stock_in/upload', // point to server-side PHP script
            // dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                $('.loading-upload').removeClass('spinner-border');
                let r = JSON.parse(response);
                if (r.status == 'invalid') {
                    $("#file").addClass("is-invalid");
                    $(".file-invalid").html(
                        r.errors.file
                    );
                    $(btn).prop('disabled', false);
                    $(btn).removeClass("disabled");
                } else if (r.status == 'error') {
                    Swal.fire(
                        '上传失败!',
                        r.msg,
                        'error'
                    );
                    $(btn).prop('disabled', false);
                    $(btn).removeClass("disabled");
                } else if (r.status == 'success') {
                    $(".is-invalid").removeClass("is-invalid");
                    Swal.fire(
                        '上传成功!',
                        '',
                        'success',
                    ).then(() => {
                        setTimeout(function () {
                            location.reload();
                        }, 700);
                    });
                }
            }
        });
    })

    $("#analysis").on("click", "#analysis-new_company", function (e) {
        table_new_company.ajax.reload();
        $('#newCompanyModal').modal('show');
    });
    $("#analysis").on("click", "#analysis-duplicate", function (e) {
        table_duplicate.ajax.reload();
        $('#duplicateModal').modal('show');
    });
    $("#analysis").on("click", "#analysis-new_goods", function (e) {
        table_new_goods.ajax.reload();
        $('#newGoodsModal').modal('show');
    });
    $("#analysis").on("click", "#analysis-new_location", function (e) {
        table_new_location.ajax.reload();
        $('#newLocationModal').modal('show');
    });
    $("#analysis").on("click", "#analysis-inconsistent", function (e) {
        table_inconsistent.ajax.reload();
        $('#inconsistentModal').modal('show');
    });
    $("#analysis").on("click", "#analysis-invalid_qty", function (e) {
        table_invalid_qty.ajax.reload();
        $('#invalidQtyModal').modal('show');
    });

    var sti_id;
    $("#table1").on("click", "tbody .btn-edit", function (e) {
        $(".is-invalid").removeClass("is-invalid");
        sti_id = $(this).data('sti-id');
        var row = table1.row($(this).parents('tr')).data();
        if (sti_id == row.sti_id) {
            $('#edit-company').val(row.company);
            $('#edit-goods_id').val(row.goods_id);
            $('#edit-name_type').val(row.name_type);
            $('#edit-unit').val(row.unit);
            $('#edit-qty').val(row.qty);
            $('#edit-location').val(row.location);
            $('#edit-remark').val(row.remark);
            $('#editModal').modal('show');
        }
    });

    $('#save-edit').on('click', function () {
        $(".is-invalid").removeClass("is-invalid");
        $.post('/tyck/stock_in/update', {
            sti_id: sti_id,
            company: $('#edit-company').val(),
            goods_id: $('#edit-goods_id').val(),
            name_type: $('#edit-name_type').val(),
            unit: $('#edit-unit').val(),
            qty: $('#edit-qty').val(),
            location: $('#edit-location').val(),
            remark: $('#edit-remark').val(),
        }, function (response) {
            let g = JSON.parse(response);
            if (g.status == 'invalid') {
                for (const [key, value] of Object.entries(g.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (g.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    g.msg,
                    'error'
                );
            } else if (g.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $(".is-invalid").removeClass("is-invalid");
                $('.form-control').val('');
                $('#editModal').modal('toggle');
                table1.ajax.reload();
                reload_analysis();
            }
        });
    });

    $("#table1").on("click", "tbody .btn-delete", function (e) {
        sti_id = $(this).data('sti-id');
        Swal.fire({
            title: '你确定要删除?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定，删除它！'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/tyck/stock_in/delete', {
                    sti_id: sti_id,
                }, function (response) {
                    let g = JSON.parse(response);
                    if (g.status == 'error') {
                        Swal.fire(
                            '删除失败!',
                            g.msg,
                            'error'
                        );
                    } else if (g.status == 'success') {
                        Swal.fire(
                            '已删除',
                            '',
                            'success'
                        );
                        table1.ajax.reload();
                        reload_analysis();
                    }
                });
            }
        })
    });

    $("#cancel").click(function () {
        Swal.fire({
            title: '你确定要取消入库?',
            text: '此操作会将已导入数据删除',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定！'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/tyck/stock_in/cancel', {
                    cancel: true,
                }, function (response) {
                    let g = JSON.parse(response);
                    if (g.status == 'error') {
                        Swal.fire(
                            '取消失败!',
                            g.msg,
                            'error'
                        );
                    } else if (g.status == 'success') {
                        Swal.fire(
                            '已取消',
                            '即将回到入库上传界面',
                            'success'
                        ).then(() => {
                            setTimeout(function () {
                                location.reload();
                            }, 700);
                        });
                    }
                });
            }
        })
    });

    $("#submit").click(function () {
        Swal.fire({
            title: '你确定要提交入库?',
            text: '此操作会生成入库记录并更新库存',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确定！'
        }).then((result) => {
            $(this).addClass("disabled");
            $(this).prop('disabled', true);
            $('#cancel').addClass("disabled");
            $('#cancel').prop('disabled', true);
            $('#reload').addClass("disabled");
            $('#reload').prop('disabled', true);
            $('.loading-import').addClass('spinner-border');
            if (result.isConfirmed) {
                $.post('/tyck/stock_in/import', {
                    submit: true,
                }, function (response) {
                    $('.loading-import').removeClass('spinner-border');
                    let g = JSON.parse(response);
                    if (g.status == 'error') {
                        Swal.fire(
                            '提交失败!',
                            g.msg,
                            'error'
                        );
                    } else if (g.status == 'success') {
                        Swal.fire(
                            '提交成功',
                            '',
                            'success'
                        ).then(() => {
                            setTimeout(function () {
                                location.reload();
                            }, 700);
                        });
                    }
                });
            }
        })
    });
});