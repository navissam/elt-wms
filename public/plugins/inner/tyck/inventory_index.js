$(document).ready(function () {

    // declare global variable
    var inv, location, location_select;

    // initialize plugins
    table2 = $("#table2").DataTable();
    select2 = $('.select2').select2();

    // function for rebuild datatables (main table)
    table1 = $("#table1").DataTable({
        "responsive": true,
        "autoWidth": false,
        language: {
            url: '/plugins/inner/datatables-lang.json'
            // url: '<?= base_url() ?>/plugins/inner/datatables-lang.json'
        },
        dom: '<"row mb-3"<"col-12"B>><"row"<"col-2"l><"col-10"f>>t<"row"<"col-3"i><"col-9"p>>',
            buttons: [{
                    extend: 'copyHtml5',
                    text: '复制',
                    className: 'btn btn-warning'
                },
                {
                    extend: 'excelHtml5',
                    text: '导出',
                    exportOptions: {
                        orthogonal: "exportxls"
                    },
                    className: 'btn btn-success'
                }
            ],
        ajax: '/tyck/inventory/getAll',
        columns: [{
                data: 'inv_id',
                render: function (data) {
                    return Number(data);
                }
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
                data: 'concate',
                render: function (data) {
                    data = data.split('-');
                    let qty = parseFloat(data[0]);
                    let safety = parseFloat(data[1]);
                    if (safety != 0) {
                        if (qty < safety || qty == 0) {
                            dt = '<span class="text-danger">'
                        } else if ((qty - safety) <= 20) {
                            dt = '<span class="text-warning">'
                        } else {
                            dt = '<span class="text-success">'
                        }
                        dt += (qty % 1 == 0) ? parseInt(qty) + '</span>' : qty + '</span>';
                    } else {
                        dt = (qty % 1 == 0) ? parseInt(qty) : qty;
                    }
                    return dt;
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
                data: 'remark'
            },
            {
                data: 'inv_id',
                render: function (data) {
                    let btn = '<button type="button" class="btn btn-primary btn-sm btn-switch" data-inv_id="' + data + '">';
                    btn += '<i class="fas fa-sync-alt" aria-hidden="true"></i>';
                    btn += '</button> ';
                    return btn;
                }
            },
        ],
    });

    getInvLocation();

    var safety_edit = false;
    var remark_edit = false;

    $('#table1').on('dblclick', 'tbody td:nth-child(8)', function (e) {
        if (!safety_edit) {
            var safety = $(this);
            var val = safety.text();
            var input = "<input min=\"0\" class=\"form-control\" id=\"edit-safety\" type=\"number\" value=\"" + val + "\">";
            safety.html(input);
            safety_edit = true;
        }
    });

    $('#table1').on('keyup', 'tbody td:nth-child(8) input#edit-safety', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var val = Number($(this).val());
        if (keycode == '27') {
            table1.cell($(this).parents('td')).data(val).draw();
            $(this).remove();
            remark_edit = false;
            safety_edit = false;
            table1.ajax.reload();
        }
        if (keycode == '13') {
            if (safety_edit) {
                var row = table1.row($(this).parents('tr')).data();
                var old = row.safety;
                var id = row.inv_id;
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
                    $.post('/tyck/inventory/update', {
                        inv_id: id,
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
                            remark_edit = false;
                            table1.ajax.reload();
                        }
                    });

                }
            }
        }
    });

    $('#table1').on('dblclick', 'tbody td:nth-child(9)', function (e) {
        if (!remark_edit) {
            var remark = $(this);
            var val = remark.text();
            var input = "<input class=\"form-control\" id=\"edit-remark\" type=\"text\" value=\"" + val + "\">";
            remark.html(input);
            remark_edit = true;
        }
    });

    $('#table1').on('keyup', 'tbody td:nth-child(9) input#edit-remark', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var val = $(this).val();
        if (keycode == '27') {
            table1.cell($(this).parents('td')).data(val).draw();
            $(this).remove();
            remark_edit = false;
            safety_edit = false;
            table1.ajax.reload();
        }
        if (keycode == '13') {
            if (remark_edit) {
                var row = table1.row($(this).parents('tr')).data();
                var old = row.remark;
                var id = row.inv_id;
                if (val == old) {
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    remark_edit = false;
                } else {
                    // console.log('ok');
                    $.post('/tyck/inventory/update', {
                        inv_id: id,
                        remark: $('#edit-remark').val(),
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
                            table1.cell($('#edit-remark').parents('td')).data(val).draw();
                            $('#edit-remark').remove();
                            remark_edit = false;
                            safety_edit = false;
                            table1.ajax.reload();
                        }
                    });

                }
            }
        }
    });

    function getInvLocation() {
        // get data location for switch
        $.get("/tyck/inventory/getLocation", function (data) {
            let object = JSON.parse(data);
            location = $.map(object, function (obj) {
                obj.id = obj.id || obj.location;
                obj.text = obj.text || obj.location;
                return obj;
            });
            location_select = $(".select2-location-switch").select2({
                data: location,
                theme: 'bootstrap4',
                dropdownParent: $("#switchModal")
            });
        });
    }

    // switch button on each row
    $("body").on("click", ".btn-switch", function (e) {
        inv_id = $(this).data('inv_id');
        let i = table1.row($(this).parents('tr')).data();
        // let i = inv.find(x => x.inv_id == inv_id);
        // console.log(i);
        let company = i.company;
        let goods_id = i.goods_id;
        let location = i.location;
        let remark = i.remark;
        let qty = i.qty;
        if (qty <= 0.00) {
            Swal.fire(
                '数量不足',
                i.msg,
                'error'
            );
        } else {
            // $(".select2-location-switch").select2().next().show();
            $("#swaps").show();
            $(".swapselect").show();
            $(".select2-location-switch").attr("id", "location2");
            $("#swapt").hide();
            $(".swaptext").hide();
            $(".is-invalid").removeClass("is-invalid");
            $('span.goods_id').html(goods_id);
            $('span.inv_id').html(inv_id);
            $('span.company').html(company);
            $('#company').val(company);
            $('#goods_id').val(goods_id);
            $('#location').val(location);
            $('#remark').val(remark);
            $('#qty').val(qty);
            $('#qty2').val(qty);
            $('#qty2').attr("max", qty);
            $('#switchModal').modal('show');
        }
    });

    $('#swaps').on('click', function () {
        $("#swapt").show();
        $(".swaptext").show();
        $(".text-location").attr("id", "location2");

        $(this).hide();
        $(".swapselect").hide();
        $(".select2-location-switch").removeAttr("id");
        // $(".select2-location-switch").select2().next().hide();
    });

    $('#swapt').on('click', function () {
        $(this).hide();
        $(".swaptext").hide();
        $(".text-location").removeAttr("id");

        $("#swaps").show();
        $(".swapselect").show();
        $(".select2-location-switch").attr("id", "location2");
        // $(".select2-location-switch").select2().next().show();
    });

    $('#save-switch').on('click', function () {
        qty = Number($('#qty').val());
        qty2 = Number($("#qty2").val());
        location = $('#location').val();
        location2 = $('#location2').val();
        // console.log(qty, qty2, location, location2);
        $(".is-invalid").removeClass("is-invalid");
        var err = false;
        if (location2 == location) {
            $("#location2").addClass("is-invalid");
            $(".location2-invalid").html("请输入 " + location + " 以外的库位");
            err = true;
        }
        if (location2 == '' || location == null) {
            $("#location2").addClass("is-invalid");
            $(".location2-invalid").html("库位不可以空");
            err = true;
        }
        if (qty2 <= 0.01) {
            $("#qty2").addClass("is-invalid");
            $(".qty2-invalid").html("数量不可低于 0.01");
            err = true;
        } else if (qty2 > qty) {
            $("#qty2").addClass("is-invalid");
            $(".qty2-invalid").html("数量不可超过库存数量");
            err = true;
        }

        if (!err) {
            $.post('/tyck/inventory/switch', {
                inv_id: inv_id,
                company: $('#company').val(),
                goods_id: $('#goods_id').val(),
                location: $('#location').val(),
                location2: $('#location2').val(),
                qty: $('#qty').val(),
                qty2: $('#qty2').val(),
                remark: $('#remark').val()
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
                    $(".is-invalid").removeClass("is-invalid");
                    $('.form-control').val('');
                    $('#switchModal').modal('toggle');
                    getInvLocation();
                    table1.ajax.reload();
                }
            });
        }
    });
});