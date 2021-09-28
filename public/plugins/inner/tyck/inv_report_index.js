$(document).ready(function () {

    $("#alert").hide();

    selectc = $('.select2-company').select2();
    selectg = $('.select2-goods_id').select2();
    selectl = $('.select2-location').select2();

    $("#advanced").on("click", function () {
        window.location.href = window.location.origin + "/tyck/report/inv_advanced";
    });

    $("#chooseAll").on("change", function () {
        $(this).prop("checked") == true ? $(".choose").prop("checked", true) : $(".choose").prop("checked", false);
    });

    $(".choose").on("change", function () {
        if ($("#inv_id").prop("checked") == true &&
            $("#company").prop("checked") == true &&
            $("#goods_id").prop("checked") == true &&
            $("#name_type").prop("checked") == true &&
            $("#unit").prop("checked") == true &&
            $("#qty").prop("checked") == true &&
            $("#location").prop("checked") == true &&
            $("#safety").prop("checked") == true &&
            $("#created_at").prop("checked") == true &&
            $("#remark").prop("checked") == true) {
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
        selectl.empty();
        selectl.select2({
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
            window.location.href = window.location.origin + "/tyck/report/inv_advanced";
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
                    return "库存报表" + today;
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
    
    $("#basicfilter").on("click", function () {
        start = $('#start').val();
        finish = $('#finish').val();
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
                [20, 50, 100],
                ["20", "50", "100"]
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
                        return "库存报表" + today;
                    },
                    exportOptions: {
                        orthogonal: "exportxls"
                    },
                    className: 'btn btn-success'
                }
            ],
            ajax: "/tyck/report/inv_report_basic/" + start + "/" + finish,
            columns: [{
                    data: 'inv_id',
                    render: function(data) {
                        return Number(data);
                    }
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
                    data: 'safety'
                },
                {
                    data: 'created_at',
                    render: function (data) {
                        data = data.split(" ");
                        return data[0];
                    }
                },
                {
                    data: 'remark'
                },
            ],
        });
    });
});