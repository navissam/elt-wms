$(document).ready(function() {

    // declare global variable
    var goods;
    var goods_id;

    table1 = $("#table1").DataTable();

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
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'goods_id',
                    render: function(data, type) {
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-edit" data-goods_id="' + data + '">';
                        btn += '<i class="fas fa-edit" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // reload datasource from ajax
    function reloadTable1() {
        let url = '/tyck/goods/getAll/';
        $.get(url, function(data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            goods = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    // save button click event (for edited data)
    $('#save-edit').on('click', function() {
            $(".is-invalid").removeClass("is-invalid");

        $.post('/goods/update', {
            goods_id: goods_id,
            name_type: $('#edit-name_type').val(),
            unit: $('#edit-unit').val(),
        }, function(response) {
            let g = JSON.parse(response);
            if (g.status == 'invalid') {
                for (const [key, value] of Object.entries(g.errors)) {
                    $(`#edit-${key}`).addClass("is-invalid");
                    $(`.${key}-invalid`).html(
                        `${value}`
                    );
                }
            } else if (g.status == 'error') {
                Swal.fire(
                    '保存失败!',
                    g.msg,
                    'error'
                );
            } else if (g.status == 'success') {
                Swal.fire(
                    '保存成功!',
                    '',
                    'success'
                );
                $(".is-invalid").removeClass("is-invalid");
                $('.form-control').val('');
                $('#editModal').modal('toggle');
                reloadTable1();
            }
        });
    });

    // edit button on each row
    $("body").on("click", ".btn-edit", function(e) {
        $(".is-invalid").removeClass("is-invalid");
        goods_id = $(this).data('goods_id');
        let g = goods.find(x => x.goods_id == goods_id);
        let name_type = g.name_type;
        let unit = g.unit;
        $('#edit-name_type').val(name_type);
        $('#edit-unit').val(unit);
        $('#editModal').modal('show');
    });
  
    $('#editModal').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('#save-edit').click();    
        }
    });
});