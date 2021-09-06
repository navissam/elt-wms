$(document).ready(function () {

    // function for rebuild datatables (main table)
        table1 = $("#table1").DataTable({
            "responsive": true,
            "autoWidth": false,
            searching: false,
            paginate: false,
            lengthMenu: [
                [50],
                ["50"]
            ],
            info: false,
            ajax: '/tyck/safety/getAllTyck',
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
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
            ],
        });

        
    $('#inv').on('click', function () {
        window.location.href = "/tyck/inventory";
    });

    $('#sti').on('click', function () {
        window.location.href = "/tyck/stock_in";
    });

    $('#sto').on('click', function () {
        window.location.href = "/tyck/stock_out";
    });

    $('#ret').on('click', function () {
        window.location.href = "/tyck/return_";
    });

    $('#scr').on('click', function () {
        window.location.href = "/tyck/scrap";
    });

    $('#swc').on('click', function () {
        window.location.href = "/tyck/switch_";
    });

    $('#history').on('click', function () {
        window.location.href = "/tyck/goods/history";
    });

    $('#print').on('click', function () {
        window.location.href = "/tyck/stock_out/sto_print";
    });

    $('#claim').on('click', function () {
        window.location.href = "/tyck/claim";
    });

    $('#report').on('click', function () {
        $("#reportModal").modal("show");
    });

    $('#sti_report').on('click', function () {
        window.location.href = "/tyck/report/sti";
    });

    $('#sto_report').on('click', function () {
        window.location.href = "/tyck/report/sto";
    });

    $('#ret_report').on('click', function () {
        window.location.href = "/tyck/report/ret";
    });

    $('#scr_report').on('click', function () {
        window.location.href = "/tyck/report/scr";
    });

    $('#swc_report').on('click', function () {
        window.location.href = "/tyck/report/swc";
    });
    
    $('#basic').on('click', function () {
        $("#basicModal").modal("show");
    });

    $('#company').on('click', function () {
        window.location.href = "/tyck/company";
    });

    $('#dept').on('click', function () {
        window.location.href = "/tyck/dept";
    });

    $('#goods').on('click', function () {
        window.location.href = "/tyck/goods";
    });
    
});