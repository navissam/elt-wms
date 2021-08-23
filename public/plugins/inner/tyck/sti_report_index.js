$(document).ready(function () {

    selectc = $('.select2-company').select2();
    selectg = $('.select2-goods_id').select2();
    selectl = $('.select2-location').select2();

    $("#chooseAll").on("change", function () {
        $(this).prop("checked") == true ? $(".choose").prop("checked", true) : $(".choose").prop("checked", false);
    });

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#start").val(today);
    $("#start").attr("max", today);
    $("#finish").val(today);
    $("#finish").attr("max", today);

    $.get('/tyck/report/getCompany/', function (data) {
        let object = JSON.parse(data);
        company = $.map(object, function (obj) {
            obj.id = obj.id || obj.companyID;
            obj.text = obj.text || obj.nameInd;
            return obj;
        });
        selectc.empty();
        selectc.select2({
            data: company,
        });
    });

    $.get('/tyck/report/getGoods/', function (data) {
        let object = JSON.parse(data);
        goods = $.map(object, function (obj) {
            obj.id = obj.id || obj.goods_id;
            obj.text = obj.text || obj.goods_id;
            return obj;
        });
        selectg.empty();
        selectg.select2({
            data: goods,
        });
    });

    $.get('/tyck/report/getLocation/', function (data) {
        let object = JSON.parse(data);
        locate = $.map(object, function (obj) {
            obj.id = obj.id || obj.location;
            obj.text = obj.text || obj.location;
            return obj;
        });
        selectl.empty();
        selectl.select2({
            data: locate,
        });
    });

    $("#alert").hide();
    // $("#swapfilter").hide();
    
    $("#savefilter").on("click", function () {
        $("#alert").hide();
        var err = false;
        choosen = [];
        c = 0;
        if ($("#sti_id").prop("checked") == true) {
            choosen.push($("#sti_id").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#company").prop("checked") == true) {
            choosen.push($("#company").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#goods_id").prop("checked") == true) {
            choosen.push($("#goods_id").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#name_type").prop("checked") == true) {
            choosen.push($("#name_type").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#unit").prop("checked") == true) {
            choosen.push($("#unit").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#qty").prop("checked") == true) {
            choosen.push($("#qty").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#location").prop("checked") == true) {
            choosen.push($("#location").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if ($("#remark").prop("checked") == true) {
            choosen.push($("#remark").val());
        } else {
            choosen.push(null);
            c = c + 1;
        }
        if (c == 8) {
            $("#alert").show();
            err = true;
        }
        // console.log(choosen);
        if (!err) {
            start = $("#start").val();
            finish = $("#finish").val();
            company = ($("#s_company").select2('val').length == 0) ? 'all' : $("#s_company").select2('val');
            goods = ($("#s_goods_id").select2('val').length == 0) ? 'all' : $("#s_goods_id").select2('val');;
            locate = ($("#s_location").select2('val').length == 0) ? 'all' : $("#s_location").select2('val');;
            // console.log(choosen, start, finish, company, goods, locate);
            // url = choosen + "/" + start + "/" + finish + "/" + company + "/" + goods + "/" + locate;

            $("#filterForm").submit();
            window.location.href = window.location.origin + "/tyck/report/sti";
        }
    });

    $("#table1").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: "/plugins/inner/datatables-lang.json",
        },
        // searching: false,
        dom: '<"row"<"col-1"B><"col-11"f>>t<"row"<"col-3"i><"col-9"p>>',
        lengthMenu: [[50],["50"]],
        buttons: [{
                extend: 'copyHtml5',
                // footer: true,
                text: '复制',
                className: 'btn btn-warning'
            },
            {
                extend: 'excelHtml5',
                // footer: true,
                text: '导出',
                filename: function fred() { return "入库报表" + today; },
                exportOptions: { orthogonal: "exportxls" },
                className: 'btn btn-success'
            }
        ],
    });

});