$(document).ready(function () {

    var inv;

    let table1 = $("#table1").DataTable();

    function createTable1(obj) {
        table1.destroy();
        table1 = $("#table1").DataTable({
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
            columns: [
                {
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

    function reloadTable1() {
        let url = "/tyck/scrap/getInv";
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable1(obj);
        });
    }
    reloadTable1();

    $("#add").on("click", function () {
        $("#addModal").modal("show");
    });

    $("body").on("click", "#table1 tbody tr", function () {
        inv = table1.row(this).data();
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $("#qty").val(0);
            $("#qty").prop("disabled", true);
        } else {
            table1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("#qty").prop("disabled", false);
        }
    });

    // save button click event (for new data)
    $("#insert").on("click", function () {
            // console.log(inv);
            if (inv == null) {
                Swal.fire(
                    "失误!", "你还没有选择物料", "error"
                );
            } else {
                goods_id = inv.goods_id;
                company = inv.company;
                scrlocation = inv.location;
                stock = (inv.qty % 1 == 0) ? parseInt(inv.qty) : inv.qty;
                $("#goods_id").val(goods_id);
                $("#company").val(company);
                $("#location").val(scrlocation);
                $("#stock").val(stock);
                $("#addModal").modal("hide");
            
        }
    });


var now = new Date();
var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);
var today = now.getFullYear() + "-" + (month) + "-" + (day); 
$("#scr_date").val(today); 
$("#scr_date").attr("max", today); $("#qty").prop("disabled", true);

$("#save").on("click", function () {
    $(".is-invalid").removeClass("is-invalid");
    var err = false;
    if ($("#scr_date").val() == '' || $("#scr_date").val() == null) {
        $("#scr_date").addClass("is-invalid");
        $(".scr_date-invalid").html("报废日期不可以空");
        err = true;
    }
    if ($("#qty").val() == '' || $("#qty").val() == null) {
        $("#qty").addClass("is-invalid");
        $(".qty-invalid").html("数量不可以空");
        err = true;
    } else if ($("#qty").val() <= 0.01) {
        $("#qty").addClass("is-invalid");
        $(".qty-invalid").html("数量不可低于 0.01")
        err = true;
    } else if (parseFloat($("#qty").val()) > inv.qty) {
        $("#qty").addClass("is-invalid");
        $(".qty-invalid").html("数量不可超过库存数量")
        err = true;
    }
    if ($("#applyPIC").val() == '' || $("#applyPIC").val() == null) {
        $("#applyPIC").addClass("is-invalid");
        $(".applyPIC-invalid").html("申请人不可以空");
        err = true;
    }
    if ($("#verifyPIC").val() == '' || $("#verifyPIC").val() == null) {
        $("#verifyPIC").addClass("is-invalid");
        $(".verifyPIC-invalid").html("审核人不可以空");
        err = true;
    }
    if ($("#reason").val() == '' || $("#reason").val() == null) {
        $("#reason").addClass("is-invalid");
        $(".reason-invalid").html("物料报废原因不可以空");
        err = true;
    }

    if (!err) {
        $.post('/tyck/scrap/save', {
            scr_date: $.trim($('#scr_date').val()),
            company: $.trim($('#company').val()),
            goods_id: $.trim($('#goods_id').val()),
            location: $('#location').val(),
            qty: $('#qty').val(),
            reason: $('#reason').val(),
            applyPIC: $('#applyPIC').val(),
            verifyPIC: $('#verifyPIC').val(),
            remark: $('#remark').val(),
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
});