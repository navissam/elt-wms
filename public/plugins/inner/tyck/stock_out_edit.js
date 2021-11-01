$(document).ready(function () {
    // declare global variable
    var inv, company, company_select, dept, dept_select;
    var deleted = [];
    var sto_id = $('#sto_id').val();
    var updated_at = $('#updated_at').val();

    // table_edit = $("#table-edit").DataTable();

    $.get("/tyck/stock_out/getCompany", function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-edit-recipient_company").select2({
            data: company,
            theme: 'bootstrap4',
        });

    });
    // $('.select2-recipient_dept').prop('disabled', true);
    $('#edit-recipient_company').on('change', function () {
        // $('.select2-recipient_dept').prop('disabled', false);
        $('.select2-edit-recipient_dept').empty();
        company = $(this).val();
        $.get("/tyck/stock_out/getDept/" + company, function (data) {
            let object = JSON.parse(data);
            dept = $.map(object, function (obj) {
                obj.id = obj.id || obj.deptName;
                obj.text = obj.text || obj.deptName;
                return obj;
            });
            dept_select = $(".select2-edit-recipient_dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });
        });

    })

    // let url = '/tyck/stock_out/sto_detail/' + sto_id;
    // $.get(url, function (data) {
    //     let obj = JSON.parse(data);
    //     inv = obj;
    //     let i = inv.find(x => x.sto_id == sto_id);
    //     sto_date = i.sto_date;
    //     recipient_company = i.recipient_company;
    //     recipient_dept = i.recipient_dept;
    //     recipient_name = i.recipient_name;
    //     $('#swaps').trigger('click');
    //     $('#edit-sto_date').val(sto_date);
    //     $('#edit-recipient_company').val(recipient_company).trigger('change');
    //     $('#edit-recipient_dept').val(recipient_dept).trigger('change');
    //     $('#edit-recipient_name').val(recipient_name);
    // });


    table_edit = $("#table-edit").DataTable({
        responsive: true,
        autoWidth: false,
        searching: false,
        paginate: false,
        info: false,
        language: {
            url: "/plugins/inner/datatables-lang.json",
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
        ajax: '/tyck/stock_out/sto_edit_detail/' + sto_id,
        columns: [{
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
                    if (data % 1 == 0)
                        return parseInt(data);
                    return data;
                }
            },
            {
                data: 'location'
            },
            {
                data: 'remark'
            },
            {
                data: 'id',
                render: function (data) {
                    let btn = '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + data + '">';
                    btn += '<i class="fas fa-times" aria-hidden="true"></i>';
                    btn += "</button> ";
                    return btn;
                },
            }
        ],
    });

    swap();

    // swap location select2/input
    function swap() {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-edit-recipient_dept").attr("id", "edit-recipient_dept");
        $(".text-edit-recipient_dept").attr("name", "edit-recipient_dept");

        $("#swaps").hide();
        $(".swapselect").hide();
        $(".select2-edit-recipient_dept").removeAttr("id");
        $(".select2-edit-recipient_dept").removeAttr("name");
    }

    $('#swaps').on('click', function () {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-edit-recipient_dept").attr("id", "edit-recipient_dept");
        $(".text-edit-recipient_dept").attr("name", "edit-recipient_dept");

        $(this).hide();
        $(".swapselect").hide();
        $(".select2-edit-recipient_dept").removeAttr("id");
        $(".select2-edit-recipient_dept").removeAttr("name");
    });

    $('#swapt').on('click', function () {
        $(this).hide();
        $(".swaptext").hide();
        $(".text-edit-recipient_dept").removeAttr("id");
        $(".text-edit-recipient_dept").removeAttr("name");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-edit-recipient_dept").attr("id", "edit-recipient_dept");
        $(".select2-edit-recipient_dept").attr("name", "edit-recipient_dept");
    });

    // frontend qty editing edit data
    var qty_edit = false;
    $('#table-edit').on('dblclick', 'tbody td:nth-child(5)', function (e) {
        if (!qty_edit) {
            row = table_edit.row(this).data();
            var qty = $(this);
            var val = qty.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-qty\" type=\"number\" value=\"" + val + "\">";
            qty.html(input);
            qty_edit = true;

            // console.log(row);
            id = row['id'],
                goods_id = row['goods_id'],
                company = row['company'],
                locations = row['location'],
                $.get('/tyck/stock_out/check_stock/' + goods_id + "/" + company + "/" + locations, function (data) {
                    stock = JSON.parse(data);
                });
            $.get('/tyck/stock_out/check_id/' + id, function (data) {
                id = JSON.parse(data);
            });
        }
    });

    $('#table-edit').on('keyup', 'tbody td:nth-child(5) input#edit-qty', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '27' || keycode == '13') {
            if (qty_edit) {
                val = Number($(this).val());
                inv_qty = Number(id[0]['qty']) + Number(stock[0]['inv_qty']);
                // console.log(inv_qty);
                if (val > inv_qty) {
                    Swal.fire(
                        "失误", "数量不可以超过库存数量", "error"
                    );
                    $(this).val(inv_qty);
                } else if (val <= 0) {
                    Swal.fire(
                        "失误", "数量必须多于零", "error"
                    );
                    $(this).val(1);
                } else {
                    table_edit.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    qty_edit = false;
                }
            }
        }
    });

    //save button click event (for new data)
    $("#save-edit").on("click", function () {
        if (!qty_edit) {
            $.get('/tyck/stock_out/check_updated/' + sto_id, function (data) {
                result = JSON.parse(data);
                if (updated_at != result[0]['updated_at']) {
                    Swal.fire(
                        '修改失败!',
                        '其他用户已修改过这个数据!',
                        'error'
                    );
                    setTimeout(function () {
                        window.top.close();
                    }, 2000);
                } else {
                    $(".is-invalid").removeClass("is-invalid");
                    var err = false;
                    if ($("#edit-recipient_dept").val() == '' || $("#edit-recipient_dept").val() == null) {
                        $("#edit-recipient_dept").addClass("is-invalid");
                        $(".recipient_dept-invalid").html("领用部门不可以空");
                        err = true;
                    }
                    if ($("#edit-recipient_name").val() == '' || $("#edit-recipient_name").val() == null) {
                        $("#edit-recipient_name").addClass("is-invalid");
                        $(".recipient_name-invalid").html("领用人不可以空");
                        err = true;
                    }
                    if ($("#edit-sto_date").val() == '' || $("#edit-sto_date").val() == null) {
                        $("#edit-sto_date").addClass("is-invalid");
                        $(".sto_date-invalid").html("出库日期不可以空");
                        err = true;
                    }

                    let sto_detail = table_edit.rows().data().toArray();
                    if (!err) {
                        $.post('/tyck/stock_out/update', {
                            sto_id: sto_id,
                            recipient_company: $('#edit-recipient_company').val(),
                            recipient_dept: $('#edit-recipient_dept').val(),
                            recipient_name: $('#edit-recipient_name').val(),
                            sto_date: $('#edit-sto_date').val(),
                            items: JSON.stringify(sto_detail),
                            deleted: JSON.stringify(deleted),
                        }, function (response) {
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
                                ).then(() => {
                                    setTimeout(function () {
                                        window.top.close();
                                    }, 500);
                                });
                            }
                        });
                    }
                }
            });
        }
    });

    //delete button click event (for edit data)
    $('#table-edit tbody').on('click', '.btn-delete', function () {
        id = $(this).data('id');
        deleted.push(id);
        let r = inv.find(x => x.id == id);
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
                // // $.post('<?= base_url(); ?>/role/delete', {
                // $.post('/tyck/stock_out/delete', {
                //     id: id,
                // }, function (response) {
                //     let r = JSON.parse(response);
                //     if (r.status == 'error') {
                //         Swal.fire(
                //             '删除失败!',
                //             r.msg,
                //             'error'
                //         );
                //     } else if (r.status == 'success') {
                table_edit
                    .row($(this).parents('tr'))
                    .remove()
                    .draw();
            }
        });
        // }
        // });
    });
});