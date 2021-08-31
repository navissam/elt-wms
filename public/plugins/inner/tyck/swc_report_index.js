$(document).ready(function () {
    
    $("#alert").hide();

    selectc = $('.select2-company').select2();
    selectg = $('.select2-goods_id').select2();
    selecto = $('.select2-old_location').select2();
    selectn = $('.select2-new_location').select2();
    
    $("#advanced").on("click", function () {
        window.location.href = window.location.origin + "/tyck/report/swc_advanced";
    });

    $("#chooseAll").on("change", function () {
        $(this).prop("checked") == true ? $(".choose").prop("checked", true) : $(".choose").prop("checked", false);
    });

    $(".choose").on("change", function () {
        if ($("#swc_date").prop("checked") == true &&
            $("#company").prop("checked") == true &&
            $("#goods_id").prop("checked") == true &&
            $("#name_type").prop("checked") == true &&
            $("#unit").prop("checked") == true &&
            $("#qty").prop("checked") == true &&
            $("#from_location").prop("checked") == true &&
            $("#to_location").prop("checked") == true &&
            $("#old_stock").prop("checked") == true &&
            $("#new_stock").prop("checked") == true &&
            $("#remark").prop("checked") == true) 
        {
            $("#chooseAll").prop("checked", true)
        } else {
            $("#chooseAll").prop("checked", false)
        }

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
        selecto.empty();
        selecto.select2({
            data: locate,
        });
        selectn.empty();
        selectn.select2({
            data: locate,
        });
    });

    $("#advfilter").on("click", function () {
        $("#alert").hide();
        var err = false;
        c = [];
        $(".choose[type=checkbox]:checked").each(function () {
            c.push($(this).val());
        });

        if (c.length <= 0) {
            $("#alert").show();
            err = true;
        }
        
        if (!err) {
            $("#filterForm").submit();
            window.location.href = window.location.origin + "/tyck/report/swc_advanced";
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
        lengthMenu: [
            [50],
            ["50"]
        ],
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
                filename: function fred() {
                    return "库位变更报表" + today;
                },
                exportOptions: {
                    orthogonal: "exportxls"
                },
                className: 'btn btn-success'
            }
        ],
    });

    table2 = $("#table2").DataTable({
        language: {
            url: "/plugins/inner/datatables-lang.json",
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
    });

    function createTable2(obj) {
        table2.destroy();
        table2 = $("#table2").DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "/plugins/inner/datatables-lang.json",
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            dom: '<"row mb-3"<"col-12 float-right"B>><"row"<"col-2"l><"col-10"f>>t<"row"<"col-3"i><"col-9"p>>',
            lengthMenu: [
                [20,50,100],
                ["20","50","100"]
            ],
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
                    filename: function fred() {
                        return "库位变更报表" + today;
                    },
                    exportOptions: {
                        orthogonal: "exportxls"
                    },
                    className: 'btn btn-success'
                }
            ],
            data: obj,
            columns: [
                {
                    data: 'swc_date'
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
                    data: 'from_location'
                },
                {
                    data: 'to_location'
                },
                {
                    data: 'old_stock'
                },
                {
                    data: 'new_stock'
                },
                {
                    data: 'remark'
                },
            ],
        });
    }

    $("#basicfilter").on("click", function () {
        start = $('#start').val();
        finish = $('#finish').val();
        // var date = new Date(f);
        // var day = ("0" + (date.getDate() + 1)).slice(-2);
        // var month = ("0" + (date.getMonth() + 1)).slice(-2);
        // var finish = date.getFullYear() + "-" + (month) + "-" + (day);
        let url = "/tyck/report/swc_report_basic/" + start + "/" + finish;
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable2(obj);
        });
    });
});