$(document).ready(function () {

    // declare global variable

    // initialize plugins
    table1 = $("#table1").DataTable();

    // function for rebuild datatables (main table)
    function createTable1(obj) {
        table1.destroy();
        table1 = $("#table1").DataTable({
            "responsive": true,
            "autoWidth": false,
            searching: false,
            paginate: false,
            info: false,
            data: obj,
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
    }

    // reload datasource from ajax
    function reloadTable1() {
        let url = '/tyck/safety/getAllTyck';
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            check = obj.length;
            // console.log(check);
            // if (check == 0) {
            //     $('#safety').hide('slow');
            // } else {
                createTable1(obj);
            // }
        });
    }

    // reload on web document first loading
    reloadTable1();

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

    // $('#report').on('click', function () {
    //     window.location.href = "/tyck/report";
    // });

    $('#history').on('click', function () {
        window.location.href = "/tyck/history";
    });

    $('#goods').on('click', function () {
        window.location.href = "/tyck/goods";
    });

});