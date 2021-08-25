$(document).ready(function () {
    
    $("#alert").hide();

    selectc = $('.select2-company').select2();
    selectg = $('.select2-goods_id').select2();
    selectl = $('.select2-location').select2();
    selectrc = $('.select2-r_company').select2();
    selectrd = $('.select2-r_dept').select2();
    selectrn = $('.select2-r_name').select2();

    $("#advanced").on("click", function () {
        window.location.href = window.location.origin + "/tyck/report/sto_advanced";
    });

    $("#chooseAll").on("change", function () {
        $(this).prop("checked") == true ? $(".choose").prop("checked", true) : $(".choose").prop("checked", false);
    });

    $(".choose").on("change", function () {
        if ($("#sto_id").prop("checked") == true &&
            $("#sto_date").prop("checked") == true &&
            $("#company").prop("checked") == true &&
            $("#goods_id").prop("checked") == true &&
            $("#name_type").prop("checked") == true &&
            $("#unit").prop("checked") == true &&
            $("#qty").prop("checked") == true &&
            $("#location").prop("checked") == true &&
            $("#recipient_company").prop("checked") == true &&
            $("#recipient_dept").prop("checked") == true &&
            $("#recipient_name").prop("checked") == true &&
            $("#remark").prop("checked") == true &&
            $("#stock_left").prop("checked") == true) 
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
        selectrc.empty();
        selectrc.select2({
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

    $.get('/tyck/report/getRDept/', function (data) {
        let object = JSON.parse(data);
        r_dept = $.map(object, function (obj) {
            obj.id = obj.id || obj.recipient_dept;
            obj.text = obj.text || obj.recipient_dept;
            return obj;
        });
        selectrd.empty();
        selectrd.select2({
            data: r_dept,
        });
    });

    $.get('/tyck/report/getRName/', function (data) {
        let object = JSON.parse(data);
        r_name = $.map(object, function (obj) {
            obj.id = obj.id || obj.recipient_name;
            obj.text = obj.text || obj.recipient_name;
            return obj;
        });
        selectrn.empty();
        selectrn.select2({
            data: r_name,
        });
    });


    $("#advfilter").on("click", function () {
        $("#alert").hide();
        var err = false;
        c = 0;
        ($("#sto_id").prop("checked") == false) ? c = c + 1: null;
        ($("#sto_date").prop("checked") == false) ? c = c + 1: null;
        ($("#company").prop("checked") == false) ? c = c + 1: null;
        ($("#goods_id").prop("checked") == false) ? c = c + 1: null;
        ($("#name_type").prop("checked") == false) ? c = c + 1: null;
        ($("#unit").prop("checked") == false) ? c = c + 1: null;
        ($("#qty").prop("checked") == false) ? c = c + 1: null;
        ($("#location").prop("checked") == false) ? c = c + 1: null;
        ($("#recipient_company").prop("checked") == false) ? c = c + 1: null;
        ($("#recipient_dept").prop("checked") == false) ? c = c + 1: null;
        ($("#recipient_name").prop("checked") == false) ? c = c + 1: null;
        ($("#remark").prop("checked") == false) ? c = c + 1: null;
        ($("#stock_left").prop("checked") == false) ? c = c + 1: null;

        if (c == 13) {
            // console.log(c);
            $("#alert").show();
            err = true;
        }
        // console.log(choosen);
        if (!err) {
            $("#filterForm").submit();
            window.location.href = window.location.origin + "/tyck/report/sto_advanced";
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
                    return "出库报表" + today;
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
                        return "出库报表" + today;
                    },
                    exportOptions: {
                        orthogonal: "exportxls"
                    },
                    className: 'btn btn-success'
                }
            ],
            data: obj,
            columns: [{
                    data: 'sto_id'
                },
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
                    data: 'remark'
                },
                {
                    data: 'stock_left'
                },
            ],
        });
    }

    $("#basicfilter").on("click", function () {
        start = $('#start').val();
        f = $('#finish').val();
        var date = new Date(f);
        var day = ("0" + (date.getDate() + 1)).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var finish = date.getFullYear() + "-" + (month) + "-" + (day);
        let url = "/tyck/report/sto_report_basic/" + start + "/" + finish;
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable2(obj);
        });
    });
});