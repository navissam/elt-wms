$(document).ready(function () {

    // declare global variable
    var goods, goods_select, location, location_select, company, company_select;


    $.get("/tyck/return_/getGoods", function (data) {
        let object = JSON.parse(data);
        goods = $.map(object, function (obj) {
            obj.id = obj.id || obj.goods_id;
            obj.text = obj.text || obj.goods_id + ' - ' + obj.name_type;
            return obj;
        });
        goods_select = $(".select2-goods_id").select2({
            data: goods,
            theme: 'bootstrap4',
            // dropdownParent: $("#switchModal")
        });
    });

    $.get("/tyck/return_/getLocation", function (data) {
        let object = JSON.parse(data);
        location = $.map(object, function (obj) {
            obj.id = obj.id || obj.location;
            obj.text = obj.text || obj.location;
            return obj;
        });
        location_select = $(".select2-ret_location").select2({
            data: location,
            theme: 'bootstrap4',
            // dropdownParent: $("#switchModal")
        });
    });

    $.get("/tyck/return_/getCompany", function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd + ' - ' + obj.nameMan;
            return obj;
        });
        company_select = $(".select2-ret_company").select2({
            data: company,
            theme: 'bootstrap4',
        });
        company_select = $(".select2-company").select2({
            data: company,
            theme: 'bootstrap4',
        });
    });
    $('#ret_dept').prop('disabled', true);
    $('#ret_company').on('change', function () {
        $('#ret_dept').prop('disabled', false);
        company = $(this).val();
        $.get("/tyck/return_/getDept/" + company, function (data) {
            let object = JSON.parse(data);
            dept = $.map(object, function (obj) {
                obj.id = obj.id || obj.deptID;
                obj.text = obj.text || obj.deptName;
                return obj;
            });
            dept_select = $(".select2-ret_dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });
            dept_select = $(".select2-dept").select2({
                data: dept,
                theme: 'bootstrap4',
            });
        });
    });

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#ret_date").val(today);
    $("#ret_date").attr("max", today);
    swap();

    function swap() {
        $('#swapt').hide();
        $(".swaptext").hide();
        $(".text-location").removeAttr("id");

        $("#swaps").show();
        $(".swapselect").show();
        $(".marg").addClass("mt-auto");
        $(".select2-ret_location").attr("id", "ret_location");
    }


    $('#swaps').on('click', function () {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-location").attr("id", "ret_location");

        $(this).hide();
        $(".swapselect").hide();
        $(".select2-ret_location").removeAttr("id");
    });

    $('#swapt').on('click', function () {
        $(this).hide();
        $(".swaptext").hide();
        $(".text-location").removeAttr("id");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-ret_location").attr("id", "ret_location");
    });

    $("#save").on("click", function () {
        $(".is-invalid").removeClass("is-invalid");
        var err = false;
        var field = [];
        if ($("#ret_dept").val() == '' || $("#ret_dept").val() == null) {
            $("#ret_dept").addClass("is-invalid");
            $(".ret_dept-invalid").html("退库部门不可以空");
            err = true;
            field.push('ret_dept');
        }
        if ($("#ret_name").val() == '' || $("#ret_name").val() == null) {
            $("#ret_name").addClass("is-invalid");
            $(".ret_name-invalid").html("退库人不可以空");
            err = true;
            field.push('ret_name');
        }
        if ($("#ret_date").val() == '' || $("#ret_date").val() == null) {
            $("#ret_date").addClass("is-invalid");
            $(".ret_date-invalid").html("退库日期不可以空");
            err = true;
            field.push('ret_date');
        }
        if ($("#ret_location").val() == '' || $("#ret_location").val() == null) {
            $("#ret_location").addClass("is-invalid");
            $(".ret_location-invalid").html("库位不可以空");
            err = true;
            field.push('ret_location');
        }
        if ($("#qty").val() == '' || $("#qty").val() == null) {
            $("#qty").addClass("is-invalid");
            $(".qty-invalid").html("数量不可以空");
            err = true;
        } else if ($("#qty").val() <= 0.01) {
            $("#qty").addClass("is-invalid");
            $(".qty-invalid").html("数量不可低于 0.01")
            err = true;
            field.push('qty');
        }

        if (!err) {
            $.post('/tyck/return_/save', {
                ret_company: $.trim($('#ret_company').val()),
                ret_dept: $.trim($('#ret_dept').val()),
                ret_name: $.trim($('#ret_name').val()),
                ret_date: $('#ret_date').val(),
                company: $('#company').val(),
                goods_id: $('#goods_id').val(),
                ret_location: $('#ret_location').val(),
                qty: $('#qty').val(),
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
                    $(".is-invalid").removeClass("is-invalid");
                    $('.form-control').val('');
                    $("#ret_date").val(today);
                    $("#ret_date").attr("max", today);
                    $('#ret_dept').prop('disabled', true);
                    $('#ret_dept').trigger('change');
                }
            });
        }
    });
});