$(document).ready(function () {

    // declare global variable
    var inv, location, location_select;

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
                    data: 'created_at'
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
                        let btn = '<button type="button" class="btn btn-primary btn-sm btn-switch" data-toggle="tooltip" title="库位转储" data-inv_id="' + data + '">';
                        btn += '<i class="fas fa-sync-alt" aria-hidden="true"></i>';
                        btn += '</button> ';
                        return btn;
                    }
                },
            ],
        });
    }

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
                        }
                    });
                    table1.cell($(this).parents('td')).data(val).draw();
                    $(this).remove();
                    safety_edit = false;
                    reloadTable1();
                }
            }
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // reload datasource from ajax
    function reloadTable1() {
        getInvLocation();
        let url = '/tyck/inventory/getAll';
        $.get(url, function (data) {
            let obj = JSON.parse(data);
            createTable1(obj);
            inv = obj;
        });
    }

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


    // reload on web document first loading
    reloadTable1();

    // switch button on each row
    $("body").on("click", ".btn-switch", function (e) {
        inv_id = $(this).data('inv_id');
        let i = inv.find(x => x.inv_id == inv_id);
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
                    reloadTable1();
                }
            });
        }
    });
});