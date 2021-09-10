$(document).ready(function () {
    // declare global variable
    var inv, row, company, company_select;
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
                data: "useFor",
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
                }
            },
        ],
    });
    let table2 = $("#table2").DataTable();

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#req_date").val(today);
    $("#req_date").attr("max", today);
    $("#qty").prop("disabled", true);

    $.get("/tyck/request/getCompany", function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-req_company").select2({
            data: company,
            theme: 'bootstrap4',
        });

    });
    $('.select2-req_dept').prop('disabled', true);
    $('#req_company').on('change', function () {
        $('.select2-req_dept').prop('disabled', false);
        $('.select2-req_dept').empty();
        $('.select2-req_dept').append('<option value="0">空值</option>');
        company = $(this).val();
        $.get("/tyck/request/getDept/" + company, function (data) {
            let object = JSON.parse(data);
            dept = $.map(object, function (obj) {
                obj.id = obj.id || obj.deptID;
                obj.text = obj.text || obj.deptName;
                return obj;
            });
            dept_select = $(".select2-req_dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });

        });

    })


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
        let url = "/tyck/request/getInv";
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable2(obj);
        });
    }

    // reload on web document first loading
    reloadTable2();
    swap();

    function swap() {
        $('#swapt').hide();
        $(".swaptext").hide();
        $(".text-req_dept").removeAttr("id");
        $(".text-req_dept").removeAttr("name");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-req_dept").attr("id", "req_dept");
        $(".select2-req_dept").attr("name", "req_dept");
    }

    $('#swaps').on('click', function () {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-req_dept").attr("id", "req_dept");
        $(".text-req_dept").attr("name", "req_dept");

        $(this).hide();
        $(".swapselect").hide();
        $(".select2-req_dept").removeAttr("id");
        $(".select2-req_dept").removeAttr("name");
    });

    $('#swapt').on('click', function () {
        $(this).hide();
        $(".swaptext").hide();
        $(".text-req_dept").removeAttr("id");
        $(".text-req_dept").removeAttr("name");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-req_dept").attr("id", "req_dept");
        $(".select2-req_dept").attr("name", "req_dept");
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
            let req_detail = table1.rows().data().toArray();
            // console.log(req_detail);
            if (check(req_detail, inv)) {
                Swal.fire(
                    "失误!", "物料已经选过了", "error"
                );
            } else {
                req_item = {
                    inv_id: inv.inv_id,
                    company: inv.company,
                    goods_id: inv.goods_id,
                    name_type: inv.goods_name,
                    unit: inv.unit,
                    location: inv.location,
                    stock: inv.qty,
                    qty: $("#qty").val(),
                    useFor: $("#useFor").val(),
                    remark: $("#remark").val(),
                };
                table1.row.add(req_item).draw();
                $('.choose').val('');
                $("#qty").prop("disabled", true);
                $("#addModal").modal("hide");
            }
        }
    });

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
        var val = Number($(this).val());
        var stock = Number(row.stock);
        if (keycode == '27' || keycode == '13') {
            if (qty_edit) {
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

    $("#print").on("click", function () {
        $(".is-invalid").removeClass("is-invalid");
        var err = false;
        var field = [];
        // if ($("#req_dept").val() == '' || $("#req_dept").val() == null) {
        //     $("#req_dept").addClass("is-invalid");
        //     $(".req_dept-invalid").html("领用部门不可以空");
        //     err = true;
        //     field.push('req_dept');
        // }
        // if ($("#req_name").val() == '' || $("#req_name").val() == null) {
        //     $("#req_name").addClass("is-invalid");
        //     $(".req_name-invalid").html("领用人不可以空");
        //     err = true;
        //     field.push('req_name');
        // }
        if ($("#req_date").val() == '' || $("#req_date").val() == null) {
            $("#req_date").addClass("is-invalid");
            $(".req_date-invalid").html("领用日期不可以空");
            err = true;
            field.push('req_date');
        }

        let req_detail = table1.rows().data().toArray();
        if (req_detail.length == 0) {
            Swal.fire(
                "失误!", "请添加物料", "warning"
            );
            err = true;
        }

        if (!err) {
            $.post('/tyck/request/save', {
                items: JSON.stringify(req_detail),
            }, function (response) {
                let r = JSON.parse(response);

                if (r.status == 'error') {
                    Swal.fire(
                        '打印失败!',
                        r.msg,
                        'error'
                    );
                } else if (r.status == 'success') {
                    $("#printReq").submit();
                    // window.location.href = window.location.origin + "/tyck/request";

                }
            })
        }
    });

    function check(req_detail, inv) {
        return !req_detail.every(element => {
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

});