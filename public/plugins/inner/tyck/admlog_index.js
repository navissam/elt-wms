$(document).ready(function() {

    // declare global variable
    var syslog;
    var syslogID;

    // initialize plugins
    table1 = $("#table1").DataTable();

    // function for rebuild datatables (main table)
    function createTable1(obj) {
        table1.destroy();
        table1 = $("#table1").DataTable({
            "order": [[3, "desc"]],
            "responsive": true,
            "autoWidth": false,
            data: obj,
            language: {
                url: '/plugins/inner/datatables-lang.json'
                // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
            },
            columns: [
                {
                    data: 'user_name',
                    render: function(data, type) {
                        return (data !=null ? data : 'NonUser');
                    }
                },
                {
                    data: 'controller'
                },
                {
                    data: 'method'
                },
                {
                    data: 'timestamp'
                },
                {
                    data: 'syslogID',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn badge bg-bbb btn-detail" data-syslogid="' + data + '">详细';
                        btn += '</button> ';
                        // let btn = '<button type="button" class="btn badge badge-success btn-detail" data-toggle="popover" title="数据" data-content="' + data + '">details</button>';
                    return btn
                    }
                },
                {
                    data: 'response'
                },
                {
                    data: 'status',
                    render: function(data, type) {
                        return (data == 1 ? '<span class="text-success">成功</span>' : '<span class="text-danger">失败</span>')
                    }
                },
            ],
        });
    }

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#start").attr("max", today);
    $("#finish").attr("max", today);
    
    // reload datasource from ajax
    function reloadTable1() {
        // let url = '<?= base_url('/syslog/getAll/') ?>';
        let url = '/tyck/admlog/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            syslog = obj;
        });
    }    
    
    $("#filter").on("click", function () {
        start = $('#start').val();
        f = $('#finish').val();
        var date = new Date(f);
        var day = ("0" + (date.getDate() + 1)).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var finish = date.getFullYear() + "-" + (month) + "-" + (day);
        
        let url = '/tyck/admlog/getByDate/' + start + "/" + finish;
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            syslog = obj;
        });
    });

    $("#reset").on("click", function () {
        $("#start").val(today);
        $("#finish").val(today);
        reloadTable1();
    });

    function pretty() {
        var ugly = document.getElementById('detail').value;
        try {
            var obj = JSON.parse(ugly);
        } catch (e) {

        }

        if (typeof obj === "object" && obj !== null) {
            var pretty = JSON.stringify(obj, undefined, 4);
            document.getElementById('detail').value = pretty;
        }
    };

    $("body").on("click", ".btn-detail", function(e) {
        syslogID = $(this).data('syslogid');
        let s = syslog.find(x => x.syslogID == syslogID);
        let data = s.data;
        $('#detail').val(data);
        pretty();
        $('#detailModal').modal('show');
    });
    reloadTable1();
});