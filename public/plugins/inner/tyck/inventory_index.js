$(document).ready(function () {

    // declare global variable
    var inv;

    // initialize plugins
    table1 = $("#table1").DataTable();
    table2 = $("#table2").DataTable();
    select2 = $('.select2').select2();

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
                    data: 'inv_id',
                },
                {
                    data: 'company'
                },
                {
                    data: 'goods_id'
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
                {
                    data: 'safety',
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
                    data: 'remark'
                },
                {
                    data: 'inv_id',
                    render: function (data) {
                        let btn = '<button type="button" class="btn bg-teal btn-sm btn-switch" data-toggle="tooltip" title="库位转储" data-inv_id="' + data + '">';
                        btn += '<i class="fas fa-sync-alt" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

    // $('#table1').on( 'click', 'tbody td:not(:first-child)', function (e) {
    //     editor.inline( this, {
    //         buttons: { label: '&gt;', fn: function () { this.submit(); } }
    //     } );
    // } );

    var safety_edit = false;
    $('#table1').on('dblclick', 'tbody td:nth-child(6)', function (e) {
        if (!safety_edit) {
            var safety = $(this);
            var val = safety.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-safety\" type=\"number\" value=\"" + val + "\">";
            safety.html(input);
            safety_edit = true;
        }
    });

    $('#table1').on('keypress', 'tbody td:nth-child(6) input#edit-safety', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if (safety_edit) {
                var val = Number($(this).val());
                var row = table1.row($(this).parents('tr')).data();
                var old = row.safety;
                var id = row.inv_id;
                if (val == old) {
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    safety_edit = false;
                } else if (val <= 0) {
                    Swal.fire(
                        "失误", "数量比许多与零", "error"
                    );
                    $(this).val(1);
                } else {
                    // console.log('ok');
                    $.post('/tyck/inventory/update', {
                        inv_id: id,
                        safety: $('#edit-safety').val(),
                    }, function (response) {
                        let g = JSON.parse(response);
                        if (g.status == 'error') {
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
                        }
                    });
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    safety_edit = false;
                }
            }
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // reload datasource from ajax
    function reloadTable1() {
        let url = '/tyck/inventory/getAll';
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable1(obj);
            inv = obj;
        });
    }

    // reload on web document first loading
    reloadTable1();

    // switch button on each row
    $("body").on("click", ".btn-switch", function (e) {
        inv_id = $(this).data('inv_id');
        let i = inv.find(x => x.inv_id == inv_id);
        // console.log(i);
        let goodsID = i.goodsID;
        let goodsName = i.goods_name;
        let locationID = i.locationID;
        let ownerID = i.ownerID;
        let ownerName = i.owner_name;
        let remark = i.remark;
        let qty = i.qty;
        if (qty <= 0.00) {
            Swal.fire(
                '数量不足',
                i.msg,
                'error'
            );
        } else {
            $(".is-invalid").removeClass("is-invalid");
            $('span.goodsID').html(goodsID);
            $('span.goodsName').html(goodsName);
            $('span.ownerName').html(ownerName);
            $('#goodsID').val(goodsID);
            $('#ownerID').val(ownerID);
            $('#locationID').val(locationID);
            $('#remark').val(remark);
            $('#qty').val(qty);
            $('#qty2').val(qty);
            $('#qty2').attr("max", qty);
            $('#switchModal').modal('show');
        }
    });

    $('#save-switch').on('click', function () {
        qty = Number($('#qty').val());
        qty2 = Number($("#qty2").val());
        location = $('#locationID').val();
        // console.log(qty);
        $(".is-invalid").removeClass("is-invalid");
        var err = false;
        if ($('#locationID2').val() == location) {
            $("#locationID2").addClass("is-invalid");
            $(".locationID2-invalid").html("请输入 " + location + " 以外的库位");
            err = true;
        }
        if ($("#qty2").val() <= 0.01) {
            $("#qty2").addClass("is-invalid");
            $(".qty2-invalid").html("数量不可低于 0.01");
            err = true;
        } else if (qty2 > qty) {
            $("#qty2").addClass("is-invalid");
            $(".qty2-invalid").html("数量不可高于 " + qty);
            err = true;
        }

        // let swc_detail = table1.rows().data().toArray();
        // // console.log(swc_detail);
        if (!err) {
            $.post('/inventory/switch', {
                inv_id: inv_id,
                goodsID: $('#goodsID').val(),
                ownerID: $('#ownerID').val(),
                locationID: $('#locationID').val(),
                locationID2: $('#locationID2').val(),
                qty: $('#qty').val(),
                qty2: $('#qty2').val(),
                remark: $('#remark').val(),
                recordType: "SWC"
                // items: JSON.stringify(swc_detail)
            }, function (response) {
                let i = JSON.parse(response);
                // if (i.status == 'invalid') {
                //     for (const [key, value] of Object.entries(i.errors)) {
                //         $(`#${key}`).addClass("is-invalid");
                //         $(`.${key}-invalid`).html(
                //             `${value}`
                //         );
                //     }
                // } else 
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
                    $(".is-invalid").removeClass("is-invalid");
                    $('.form-control').val('');
                    $('#switchModal').modal('toggle');
                    reloadTable1();
                }
            });
        }
    });
});