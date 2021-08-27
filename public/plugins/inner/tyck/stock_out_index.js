$(document).ready(function () {
    // declare global variable
    var inv, company, company_select;
    // initialize plugins
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
                data: "goods_id",
                render: function () {
                    let btn =
                        '<button type="button" class="btn btn-danger btn-sm btn-delete">';
                    btn += '<i class="fas fa-times" aria-hidden="true"></i>';
                    btn += "</button> ";
                    return btn;
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
        dept = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-recipient_company").select2({
            data: dept,
            theme: 'bootstrap4',
        });

    });

    // function for rebuild datatables (restore table)
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
    // function reloadTable1() {
    //     let url = "/stock_in/getAll";
    //     $.get(url, function (data, status) {
    //         let obj = JSON.parse(data);
    //         createTable1(obj);
    //         role = obj;
    //     });
    // }

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

    // save button click event (for new data)
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
                    qty: $("#qty").val(),
                    remark: $("#remark").val(),
                };
                table1.row.add(sto_item).draw();
                $("#addModal").modal("hide");
            }
        }
    });

    var qty_edit = false;
    $('#table1').on('dblclick', 'tbody td:nth-child(5)', function (e) {
        if (!qty_edit) {
            var qty = $(this);
            var val = qty.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-qty\" type=\"number\" value=\"" + val + "\">";
            qty.html(input);
            qty_edit = true;
        }
    });

    $('#table1').on('keypress', 'tbody td:nth-child(5) input#edit-qty', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if (qty_edit) {
                var val = Number($(this).val());
                var stock = inv.qty;
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
                    setTimeout(function () {
                        //     window.open(window.location.origin + "/inventory/sto_print/", '_blank');
                        // window.location.href = window.location.origin + "/tyck/inventory/";
                        window.location.href = "/tyck/inventory/";
                    }, 2000);
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

    $('#table1 tbody').on('click', '.btn-delete', function () {
        table1
            .row($(this).parents('tr'))
            .remove()
            .draw();
    });

    table_print = $("#table_print").DataTable({
        language: {
            url: "/plugins/inner/datatables-lang.json",
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
    });

    $('.c_print').on('change', function () {
        var val = $(this).val();
        console.log(val);
        var parent = $(this);
        $("input[value='" + val + "']").each(function () {
            $(this).not(parent).attr('disabled', parent.is(':checked'));
        });

        // // cache the elements
        // var $chk = $("[name='c_print[]']");
        // // bind change event handler
        // $chk.change(function () {
        //     $chk
        //         // remove current element
        //         .not(this)
        //         // filter out element with same value
        //         .filter('[value="' + this.value + '"]')
        //         // update disabled property based on the checked property
        //         .prop('disabled', this.checked);
        // })

    })


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
            columns: [
                {
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
                    data: 'qty'
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
            $("#print").attr("type", "button");
            $("#print").addClass("btn btn-success float-right");
            document.getElementById("print").innerHTML = '<i class="fas fa-print"></i> 打印';
            // $("#checkB").addClass("custom-control custom-checkbox");
            // x = '<input class="custom-control-input custom-control-input-danger" type="checkbox" id="chooseAll">';
            // x += '<label for="chooseAll" class="custom-control-label float-right">全选<label>';
            // document.getElementById("checkB").innerHTML = x;
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
});