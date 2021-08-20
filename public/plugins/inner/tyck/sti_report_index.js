$(document).ready(function () {

    let table1 = $("#table1").DataTable();
    selectc = $('.select2-company').select2();
    selectg = $('.select2-goods_id').select2();
    selectl = $('.select2-location').select2();

    // function createTable1(obj) {
    //     table1.destroy();
    //     table1 = $("#table1").DataTable({
    //         pageLength: 5,
    //         lengthMenu: [
    //             [5, 10, 20],
    //             [5, 10, 20]
    //         ],
    //         responsive: true,
    //         autoWidth: false,
    //         language: {
    //             url: "/plugins/inner/datatables-lang.json",
    //             // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
    //         },
    //         data: obj,
    //         columns: [
    //             {
    //                 data: 'company'
    //             },
    //             {
    //                 data: 'location'
    //             },
    //             {
    //                 data: 'goods_id'
    //             },
    //             {
    //                 data: 'goods_name'
    //             },
    //             {
    //                 data: 'unit'
    //             },
    //             {
    //                 data: 'qty',
    //                 render: function (data) {
    //                     if (data % 1 == 0)
    //                         return parseInt(data);
    //                     return data;
    //                 }
    //             },
    //             {
    //                 data: 'updated_at'
    //             },
    //         ],
    //     });
    // }

    // function reloadTable1() {
    //     let url = "/tyck/scrap/getInv";
    //     $.get(url, function (data) {
    //         let obj = JSON.parse(data);
    //         createTable1(obj);
    //     });
    // }
    // reloadTable1();

    $("#chooseAll").on("change", function () {
        ($(this).prop("checked") == true) ? $(".choose").prop("checked", true): $(".choose").prop("checked", false);
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
});