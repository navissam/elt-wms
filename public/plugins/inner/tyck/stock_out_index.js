$(document).ready(function () {
    // declare global variable
    var inv, row, company, company_select, dept, dept_select;
    // insert item table
    let table1 = $("#table1").DataTable({
        searching: false,
        paging: false,
        info: false,
        language: {
            url: "/plugins/inner/datatables-lang.json",
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
        columns: [{
                data: "company",
            },
            {
                data: "goods_id",
            },
            {
                data: "name_type",
            },
            {
                data: "unit",
            },
            {
                data: "qty",
            },
            {
                data: "location",
            },
            {
                data: "remark",
            },
            {
                data: "stock",
                render: function (data) {
                    let btn =
                        '<button type="button" class="btn btn-danger btn-sm btn-delete">';
                    btn += '<i class="fas fa-times" aria-hidden="true"></i>';
                    btn += "</button> ";
                    return btn + '<input type="hidden" class="form-control" id="stock" value="' + data + '">';
                },
            },
        ],
    });
    let table2 = $("#table2").DataTable();

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#sto_date").val(today);
    $("#sto_date").attr("max", today);
    $("#qty").prop("disabled", true);

    $.get("/tyck/stock_out/getCompany", function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-recipient_company").select2({
            data: company,
            theme: 'bootstrap4',
        });
        company_select = $(".select2-edit-recipient_company").select2({
            data: company,
            theme: 'bootstrap4',
        });

    });
    $('.select2-recipient_dept').prop('disabled', true);
    $('#recipient_company').on('change', function () {
        $('.select2-recipient_dept').prop('disabled', false);
        $('.select2-recipient_dept').empty();
        company = $(this).val();
        $.get("/tyck/stock_out/getDept/" + company, function (data) {
            let object = JSON.parse(data);
            dept = $.map(object, function (obj) {
                obj.id = obj.id || obj.deptName;
                obj.text = obj.text || obj.deptName;
                return obj;
            });
            dept_select = $(".select2-recipient_dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });
            dept_select = $(".select2-edit-recipient_dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });

        });

    })

    // table add goods for stockout
    function createTable2(obj) {
        table2.destroy();
        table2 = $("#table2").DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20],
                [5, 10, 20]
            ],
            responsive: true,
            autoWidth: false,
            language: {
                url: "/plugins/inner/datatables-lang.json",
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
            columns: [{
                    data: 'company'
                },
                {
                    data: 'location'
                },
                {
                    data: 'goods_id'
                },
                {
                    data: 'goods_name'
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
                    data: 'updated_at'
                },
            ],
        });
    }

    // reload datasource from ajax
    function reloadTable2() {
        let url = "/tyck/stock_out/getInv";
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable2(obj);
        });
    }

    // reload on web document first loading
    reloadTable2();
    swap();

    // swap location select2/input
    function swap() {
        $('#swapt').hide();
        $(".swaptext").hide();
        $(".text-recipient_dept").removeAttr("id");
        $(".text-recipient_dept").removeAttr("name");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-recipient_dept").attr("id", "recipient_dept");
        $(".select2-recipient_dept").attr("name", "recipient_dept");
    }

    $('#swaps').on('click', function () {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-recipient_dept").attr("id", "recipient_dept");
        $(".text-recipient_dept").attr("name", "recipient_dept");

        $(this).hide();
        $(".swapselect").hide();
        $(".select2-recipient_dept").removeAttr("id");
        $(".select2-recipient_dept").removeAttr("name");
    });

    $('#swapt').on('click', function () {
        $(this).hide();
        $(".swaptext").hide();
        $(".text-recipient_dept").removeAttr("id");
        $(".text-recipient_dept").removeAttr("name");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-recipient_dept").attr("id", "recipient_dept");
        $(".select2-recipient_dept").attr("name", "recipient_dept");
    });
    // add item button click event (for new data)
    $("#add").on("click", function () {
        // reloadTable2();
        $("#addModal").modal("show");
    });


    $("body").on("click", "#table2 tbody tr", function () {
        inv = table2.row(this).data();
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $("#qty").val(0);
            $("#qty").prop("disabled", true);
        } else {
            table2.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("#qty").prop("disabled", false);
            if (inv.qty < parseInt($("#qty").val())) {
                $("#qty").val(inv.qty);
            }
            $("#qty").attr("max", inv.qty);
        }
    });

    //insert button click event (for new data)
    $("#insert").on("click", function () {
        if (inv == null) {
            Swal.fire(
                "失误!", "你还没有选择物料", "error"
            );
        } else if ($("#qty").val() <= 0.01) {
            $("#qty").addClass("is-invalid");
            $(".qty-invalid").html("数量不可低于 0.01")
        } else if ($("#qty").val() > Number(inv.qty)) {
            $("#qty").addClass("is-invalid");
            $(".qty-invalid").html("数量不可以超过库存数量");
        } else {
            $(".is-invalid").removeClass("is-invalid");
            let sto_detail = table1.rows().data().toArray();
            // console.log(sto_detail);
            if (check(sto_detail, inv)) {
                Swal.fire(
                    "失误!", "物料已经选过了", "error"
                );
            } else {
                sto_item = {
                    inv_id: inv.inv_id,
                    company: inv.company,
                    goods_id: inv.goods_id,
                    name_type: inv.goods_name,
                    unit: inv.unit,
                    location: inv.location,
                    stock: inv.qty,
                    qty: $("#qty").val(),
                    remark: $("#remark").val(),
                };
                table1.row.add(sto_item).draw();
                $("#addModal").modal("hide");
            }
        }
    });

    // frontend qty editing new data
    var qty_edit = false;
    $('#table1').on('dblclick', 'tbody td:nth-child(5)', function (e) {
        if (!qty_edit) {
            row = table1.row(this).data();
            var qty = $(this);
            var val = qty.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-qty\" type=\"number\" value=\"" + val + "\">";
            qty.html(input);
            qty_edit = true;
        }
    });

    $('#table1').on('keyup', 'tbody td:nth-child(5) input#edit-qty', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '27' || keycode == '13') {
            if (qty_edit) {
                var val = Number($(this).val());
                var stock = Number(row.stock);
                if (val > stock) {
                    Swal.fire(
                        "失误", "数量不可以超过库存数量", "error"
                    );
                    $(this).val(stock);
                } else if (val <= 0) {
                    Swal.fire(
                        "失误", "数量必须多于零", "error"
                    );
                    $(this).val(1);
                } else {
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    qty_edit = false;
                }
            }
        }
    });

    //save button click event (for new data)
    $("#save").on("click", function () {
        $(".is-invalid").removeClass("is-invalid");
        var err = false;
        var field = [];
        // if ($("#recipient_company").val() == '' || $("#recipient_company").val() == null) {
        //     $("#recipient_company").addClass("is-invalid");
        //     $(".recipient_company-invalid").html("领用公司不可以空");
        //     err = true;
        //     field.push('recipient_company');
        // }
        if ($("#recipient_dept").val() == '' || $("#recipient_dept").val() == null) {
            $("#recipient_dept").addClass("is-invalid");
            $(".recipient_dept-invalid").html("领用部门不可以空");
            err = true;
            field.push('recipient_dept');
        }
        if ($("#recipient_name").val() == '' || $("#recipient_name").val() == null) {
            $("#recipient_name").addClass("is-invalid");
            $(".recipient_name-invalid").html("领用人不可以空");
            err = true;
            field.push('recipient_name');
        }
        if ($("#sto_date").val() == '' || $("#sto_date").val() == null) {
            $("#sto_date").addClass("is-invalid");
            $(".sto_date-invalid").html("出库日期不可以空");
            err = true;
            field.push('sto_date');
        }

        let sto_detail = table1.rows().data().toArray();
        if (sto_detail.length == 0) {
            Swal.fire(
                "失误!", "请添加物料", "warning"
            );
            err = true;
        }

        if (!err) {
            $.post('/tyck/stock_out/save', {
                recipient_company: $.trim($('#recipient_company').val()),
                recipient_dept: $.trim($('#recipient_dept').val()),
                recipient_name: $.trim($('#recipient_name').val()),
                sto_date: $('#sto_date').val(),
                items: JSON.stringify(sto_detail),
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
                    );
                    $(".is-invalid").removeClass("is-invalid");
                    $('.form-control').val('');
                    table1
                        .clear()
                        .draw();
                    $("#sto_date").val(today);
                    $("#sto_date").attr("max", today);
                    swap();
                    $('#recipient_dept').prop('disabled', true);
                    $('#recipient_dept').trigger('change');
                    reloadTable2();
                }
            });
        }
    });

    function check(sto_detail, inv) {
        return !sto_detail.every(element => {
            // console.log(element);
            // console.log(inv);
            // console.log(element.inv_id == inv.inv_id);
            if (element.inv_id === inv.inv_id) {
                return false;
            }
            return true;
        });
    }

    //delete button click event (for new data)
    $('#table1 tbody').on('click', '.btn-delete', function () {
        table1
            .row($(this).parents('tr'))
            .remove()
            .draw();
    });

    table_print = $("#table_print").DataTable({
        language: {
            url: "/plugins/inner/datatables-lang.json",
        },
    });

    table_list = $("#table-list").DataTable({
        language: {
            url: "/plugins/inner/datatables-lang.json",
        },
    });
    table_detail = $("#table-detail").DataTable();

    $('.c_print').on('change', function () {
        var val = $(this).val();
        // console.log(val);
        var parent = $(this);
        $("input[value='" + val + "']").each(function () {
            $(this).not(parent).attr('disabled', parent.is(':checked'));
        });
    });

    // table for print (单据交接)
    function createTable3(obj) {
        table_print.destroy();
        table_print = $("#table_print").DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "/plugins/inner/datatables-lang.json",
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
            columns: [{
                    data: 'sto_date'
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
                        if (data % 1 == 0)
                            return parseInt(data);
                        return data;
                    }
                },
                {
                    data: 'location'
                },
                {
                    data: 'recipient_company'
                },
                {
                    data: 'recipient_dept'
                },
                {
                    data: 'recipient_name'
                },
                {
                    data: 'sto_id',
                },
            ],
        });
    }

    // print filter
    $("#printfilter").on("click", function () {
        tgl = $('#datefilter').val();
        var date = new Date(tgl);
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var finish = date.getFullYear() + "-" + (month) + "-" + (day);
        let url = "/tyck/stock_out/sto_print_data/" + finish;
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable3(obj);
            // console.log(obj);
            if (obj.length > 0) {
                $("#print").attr("type", "button");
                $("#print").addClass("btn btn-success float-right");
                document.getElementById("print").innerHTML = '<i class="fas fa-print"></i> 打印';
            } else {
                $("#print").removeAttr("type", "button");
                $("#print").removeClass("btn btn-success float-right");
                document.getElementById("print").innerHTML = '';
            }
        });
    });

    $("#print").on("click", function () {
        Swal.fire(
            '打印成功!',
            '',
            'success'
        );
        $("#printForm").submit();
        window.location.href = window.location.origin + "/tyck/stock_out/sto_print/";
    });

    // table for stockout list
    function createTable4(obj) {
        table_list.destroy();
        table_list = $("#table-list").DataTable({
            "order": [
                [1, "desc"]
            ],
            responsive: true,
            autoWidth: false,
            language: {
                url: "/plugins/inner/datatables-lang.json",
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
            columns: [{
                    data: 'sto_id'
                },
                {
                    data: 'sto_date'
                },
                {
                    data: 'recipient_company'
                },
                {
                    data: 'recipient_dept'
                },
                {
                    data: 'recipient_name'
                },
                {
                    data: 'sto_id',
                    render: function (data) {
                        let btn = '<button type="button" class="btn btn-info btn-sm btn-record" data-sto-id="' + data + '">';
                        btn += '<i class="fas fa-history" aria-hidden="true"></i>';
                        btn += '</button> ';
                        btn += '<button type="button" class="btn btn-primary btn-sm btn-edit" data-sto-id="' + data + '">';
                        btn += '<i class="fa fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // table for stockout detail
    function createTable5(obj) {
        table_detail.destroy();
        table_detail = $("#table-detail").DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20],
                [5, 10, 20]
            ],
            "order": [
                [1, "desc"]
            ],
            responsive: true,
            autoWidth: false,
            language: {
                url: "/plugins/inner/datatables-lang.json",
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
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
                    data: 'qty',
                    render: function (data) {
                        if (data % 1 == 0)
                            return parseInt(data);
                        return data;
                    }
                },
                {
                    data: 'unit'
                },
                {
                    data: 'location'
                },
                {
                    data: 'remark'
                }
            ],
        });
    }

    // list filter
    $("#listfilter").on("click", function () {
        start = $('#start').val();
        finish = $('#finish').val();
        let url = "/tyck/stock_out/sto_list_data/" + start + "/" + finish;
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            inv = obj;
            createTable4(obj);
        });
    });

    $("body").on("click", ".btn-record", function (e) {
        sto_id = $(this).data('sto-id');
        let i = inv.find(x => x.sto_id == sto_id);
        // console.log(i);
        let sto_date = i.sto_date;
        let recipient_company = i.recipient_company;
        let recipient_dept = i.recipient_dept;
        let recipient_name = i.recipient_name;
        // console.log(i);
        $.get('/tyck/stock_out/sto_detail/' + sto_id, function (response) {
            let r = JSON.parse(response);
            $('span.sto_id').html(sto_id);
            $('span.sto_date').html(sto_date);
            $('span.recipient_company').html(recipient_company);
            $('span.recipient_dept').html(recipient_dept);
            $('span.recipient_name').html(recipient_name);
            createTable5(r);
            $('#detailModal').modal('show');
        });
    });

    $("body").on("click", ".btn-edit", function (e) {
        sto_id = $(this).data('sto-id');
        window.open(window.location.origin + "/tyck/stock_out/sto_edit_view/" + sto_id, '_blank');
    });
});