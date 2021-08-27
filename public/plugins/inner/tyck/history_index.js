$(document).ready(function () {

    var goods;
    // declare global variable
    table1 = $("#table1").DataTable();
    table2 = $("#table2").DataTable();

    // function for rebuild datatables (main table)
    function createTable1(obj) {
        table1.destroy();
        table1 = $("#table1").DataTable({
            "responsive": true,
            "autoWidth": false,
            data: obj,
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            columns: [{
                    data: 'goods_id',
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
                    data: 'updated_at'
                },
                {
                    data: 'goods_id',
                    render: function (data) {
                        let btn = '<button type="button" class="btn btn-info btn-sm btn-record" data-goods_id="' + data + '">';
                        btn += '<i class="fas fa-history" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    function createTable2(obj) {
        table2.destroy();
        table2 = $("#table2").DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 20], [5, 10, 20]],
            "order": [[4, "desc"]],
            "responsive": true,
            "autoWidth": false,
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            data: obj,
            columns: [{
                data: 'TYPE',
                render: function (data) {
                    var sta;
                    if (data == 'STI') {
                        sta = '<span class="text-success">入库</span>';
                    } else if (data == 'STO') {
                        sta = '<span class="text-primary">出库</span>';
                    } else if (data == 'RET') {
                        sta = '<span class="text-teal">退库</span>';
                    } else if (data == 'SCR') {
                        sta = '<span class="text-danger">报废</span>';
                    } else if (data == 'SWC') {
                        sta = '<span class="text-info">转储</span>';
                    }
                    return sta;
                }
            },
            {
                data: 'qty',
                render: function (data) {
                        if (data % 1 == 0)
                            return parseInt(data);
                        return data;
                    }
                // data: 'concate',
                // render: function (data) {
                //     data = data.split('=');
                //     if( data[0] == '+') {
                //         stt = '<span class="text-success">' + data[0];
                //     } else {
                //         stt = '<span class="text-danger">' + data[0];
                //     }
                //     if (data[1] % 1 == 0){
                //         stt += parseInt(data[1]) + '</span>';
                //     } else {
                //         stt += data[1] + '</span>';
                //     }
                //     return stt;
                // }
            },
            {
                data: 'company'
            },
            {
                data: 'location'
            },
            {
                data: 'created_at'
            },
            {
                data: 'remark',
            },
            // {
            //     data: 'recordID',
            //     render: function (data) {
            //         let type = data.substring(0, 3).toLowerCase();
            //         if (type == 'sti' || type == 'swc') {
            //             return '';
            //         }
            //         let btn = '<a target="_blank" class="btn btn-info btn-sm" href="/inventory/' + type + '_print/' + data + '">';
            //         btn += '<i class="fas fa-search" aria-hidden="true"></i>';
            //         btn += '</a> ';
            //         return btn;
            //     }
            // },
            ],
        });
    }

    // reload datasource from ajax
    function reloadTable1() {
        let url = '/tyck/goods/getHistory/';
        $.get(url, function (data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            goods = obj;
        });
    }
    // reload on web document first loading
    reloadTable1();

    $("body").on("click", ".btn-record", function (e) {
        g = $(this).data('goods_id');
        console.log(g);
        let i = goods.find(x => x.goods_id == g);
        let goods_id = i.goods_id;
        let name_type = i.name_type;
        let unit = i.unit;
        // console.log(i);
        $.get('/tyck/goods/record/' + goods_id, function (response) {
            let r = JSON.parse(response);
            // console.log(r);
            $('span.goods_id').html(goods_id);
            $('span.name_type').html(name_type);
            $('span.unit').html(unit);
            createTable2(r);
            $('#recordModal').modal('show');
        });
    });

});