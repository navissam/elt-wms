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

        table1.ajax.reload();
        
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

    $('#history').on('click', function () {
        window.location.href = "/tyck/history";
    });

    $('#goods').on('click', function () {
        window.location.href = "/tyck/goods";
    });

    $('#print').on('click', function () {
        window.location.href = "/tyck/stock_out/sto_print";
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

});