$(document).ready(function () {

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
                    data: 'safety',
                    render: function (data) {
                        if (data % 1 == 0)
                            return parseInt(data);
                        return data;
                    }
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'goods_id',
                    render: function (data, type) {
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
        $.get(url, function (data, status) {
            let obj = JSON.parse(data);
            createTable1(obj);
            goods = obj;
        });
    }

    var safety_edit = false;
    $('#table1').on('dblclick', 'tbody td:nth-child(4)', function (e) {
        if (!safety_edit) {
            var safety = $(this);
            var val = safety.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-safety\" type=\"number\" value=\"" + val + "\">";
            safety.html(input);
            safety_edit = true;
        }
    });

    $('#table1').on('keypress', 'tbody td:nth-child(4) input#edit-safety', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if (safety_edit) {
                var val = Number($(this).val());
                var row = table1.row($(this).parents('tr')).data();
                var old = row.safety;
                var id = row.goods_id;
                if (val == old) {
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    safety_edit = false;
                } else if (val < 0) {
                    Swal.fire(
                        "失误", "数量不可低于零", "error"
                    );
                    $(this).val(0);
                } else {
                    // console.log('ok');
                    $.post('/tyck/goods/update_safety', {
                        goods_id: id,
                        safety: $('#edit-safety').val(),
                    }, function (response) {
                        let i = JSON.parse(response);
                        if (i.status == 'error') {
                            Swal.fire(
                                '保存失败!',
                                i.msg,
                                'error'
                            );
                        } else if (i.status == 'success') {
                            Swal.fire(
                                '保存成功!',
                                '',
                                'success'
                            );
                            table1.cell($('#edit-safety').parents('td')).data(val).draw();
                            $('#edit-safety').remove();
                            safety_edit = false;
                            reloadTable1();
                        }
                    });

                }
            }
        }
    });

    // reload on web document first loading
    reloadTable1();

    // save button click event (for edited data)
    $('#save-edit').on('click', function () {
        $(".is-invalid").removeClass("is-invalid");

        $.post('/tyck/goods/update', {
            goods_id: goods_id,
            name_type: $('#edit-name_type').val(),
            unit: $('#edit-unit').val(),
            safety: $('#edit-safety').val(),
        }, function (response) {
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
    $("body").on("click", ".btn-edit", function (e) {
        $(".is-invalid").removeClass("is-invalid");
        goods_id = $(this).data('goods_id');
        let g = goods.find(x => x.goods_id == goods_id);
        let name_type = g.name_type;
        let unit = g.unit;
        let safety = g.safety;
        $('#edit-name_type').val(name_type);
        $('#edit-unit').val(unit);
        $('#edit-safety').val(safety);
        $('#editModal').modal('show');
    });

    $('#editModal').keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#save-edit').click();
        }
    });
});